<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\BienEspecificacion;
use App\Models\BienHistorial;
use App\Models\MantenimientoBien;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BienController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $rol = $user->rol->nombre;

        $query = Bien::with(['area', 'tipoBien', 'especificaciones']);

        if ($rol === 'Area Usuaria') {
            $areaIds = $user->asignaciones()->where('estado_asignacion', 'activo')->pluck('area_id');
            $query->whereIn('bienes.area_id', $areaIds);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%")
                  ->orWhere('numero_serie', 'like', "%{$search}%")
                  ->orWhere('codigo_patrimonial', 'like', "%{$search}%")
                  ->orWhere('ubicacion', 'like', "%{$search}%")
                  ->orWhereHas('tipoBien', function ($q2) use ($search) {
                      $q2->where('nombre', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('estado')) {
            $query->where('bienes.estado', $request->estado);
        }

        if ($request->filled('tipo_bien_id')) {
            $query->where('bienes.tipo_bien_id', $request->tipo_bien_id);
        }

        if ($request->filled('area_id')) {
            $query->where('bienes.area_id', $request->area_id);
        }

        $bienes = $query->orderBy('bienes.created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        $bienes->getCollection()->transform(function ($bien) {
            $area = $bien->area;
            $responsable = null;
            $cargoResponsable = null;
            if ($area) {
                $designacion = $area->jefeActual()->with('usuario')->first();
                if ($designacion && $designacion->usuario) {
                    $responsable = $designacion->usuario->nombres . ' ' . $designacion->usuario->apellidos;
                    $cargoResponsable = $designacion->cargo ?? $designacion->tipo_designacion;
                }
            }
            $bien->responsable_nombre = $responsable;
            $bien->responsable_cargo = $cargoResponsable;
            return $bien;
        });

        return response()->json($bienes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_bien_id' => 'required|exists:tipo_bienes,id',
            'area_id' => 'required|exists:areas,id',
            'estado' => 'required|in:operativo,mantenimiento,programado,inactivo,baja',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'numero_serie' => 'nullable|string|max:255',
            'codigo_patrimonial' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:500',
            'observaciones' => 'nullable|string',
            'especificaciones' => 'nullable|array',
            'especificaciones.*.campo' => 'required|string|max:255',
            'especificaciones.*.valor' => 'nullable|string|max:500',
        ]);

        $codigo = $this->generarCodigo($request->tipo_bien_id, $request->area_id);

        DB::beginTransaction();
        try {
            $bien = Bien::create([
                'codigo' => $codigo,
                'tipo_bien_id' => $request->tipo_bien_id,
                'area_id' => $request->area_id,
                'estado' => $request->estado,
                'marca' => $request->marca,
                'modelo' => $request->modelo,
                'numero_serie' => $request->numero_serie,
                'codigo_patrimonial' => $request->codigo_patrimonial,
                'ubicacion' => $request->ubicacion,
                'observaciones' => $request->observaciones,
            ]);

            if ($request->filled('especificaciones')) {
                foreach ($request->especificaciones as $esp) {
                    $bien->especificaciones()->create([
                        'campo' => $esp['campo'],
                        'valor' => $esp['valor'] ?? null,
                    ]);
                }
            }

            $this->registrarHistorial($bien, 'creacion', 'Bien registrado en el sistema');

            DB::commit();

            return response()->json([
                'message' => 'Bien registrado correctamente',
                'data' => $bien->load(['area', 'tipoBien', 'especificaciones']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al registrar el bien: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            return response()->json(['message' => 'ID inválido'], 404);
        }

        $bien = Bien::with(['area', 'tipoBien', 'especificaciones', 'mantenimientos.tecnico', 'historial'])
            ->findOrFail($id);

        $area = $bien->area;
        $responsable = null;
        $cargoResponsable = null;

        if ($area) {
            $designacion = $area->jefeActual()->with('usuario')->first();
            if ($designacion) {
                $responsable = $designacion->usuario;
                $cargoResponsable = $designacion->cargo ?? $designacion->tipo_designacion;
            }
        }

        return response()->json([
            'data' => $bien,
            'responsable' => $responsable,
            'cargo_responsable' => $cargoResponsable,
        ]);
    }

    public function update(Request $request, $id)
    {
        $bien = Bien::findOrFail($id);

        $request->validate([
            'tipo_bien_id' => 'sometimes|exists:tipo_bienes,id',
            'area_id' => 'sometimes|exists:areas,id',
            'estado' => 'sometimes|in:operativo,mantenimiento,programado,inactivo,baja',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'numero_serie' => 'nullable|string|max:255',
            'codigo_patrimonial' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:500',
            'observaciones' => 'nullable|string',
            'especificaciones' => 'nullable|array',
            'especificaciones.*.campo' => 'required|string|max:255',
            'especificaciones.*.valor' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $antes = $bien->only(['estado', 'marca', 'modelo', 'numero_serie', 'codigo_patrimonial', 'ubicacion', 'observaciones']);

            $bien->update($request->only([
                'tipo_bien_id', 'area_id', 'estado', 'marca', 'modelo',
                'numero_serie', 'codigo_patrimonial', 'ubicacion', 'observaciones',
            ]));

            $despues = $bien->only(['estado', 'marca', 'modelo', 'numero_serie', 'codigo_patrimonial', 'ubicacion', 'observaciones']);

            $cambios = [];
            foreach ($despues as $campo => $valor) {
                if ($antes[$campo] !== $valor) {
                    $cambios[] = "{$campo}: \"{$antes[$campo]}\" → \"{$valor}\"";
                }
            }

            if (!empty($cambios)) {
                $this->registrarHistorial($bien, 'actualizacion', implode(', ', $cambios));
            }

            if ($request->has('especificaciones')) {
                $bien->especificaciones()->delete();
                foreach ($request->especificaciones as $esp) {
                    $bien->especificaciones()->create([
                        'campo' => $esp['campo'],
                        'valor' => $esp['valor'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Bien actualizado correctamente',
                'data' => $bien->load(['area', 'tipoBien', 'especificaciones']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al actualizar el bien'], 500);
        }
    }

    public function destroy($id)
    {
        $bien = Bien::findOrFail($id);
        $bien->delete();

        return response()->json(['message' => 'Bien eliminado correctamente']);
    }

    public function porArea(Request $request)
    {
        $user = Auth::user();
        $rol = $user->rol->nombre;

        $areasQuery = \App\Models\Area::with(['asignaciones' => function ($q) {
            $q->where('estado_asignacion', 'activo')->with('usuario');
        }]);

        if ($rol === 'Area Usuaria') {
            $areaIds = $user->asignaciones()->where('estado_asignacion', 'activo')->pluck('area_id');
            $areasQuery->whereIn('areas.id', $areaIds);
        }

        if ($request->filled('area_id')) {
            $areasQuery->where('areas.id', $request->area_id);
        }

        $search = $request->input('search', '');

        if ($search) {
            $areasQuery->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhereHas('bienes', function ($q2) use ($search) {
                      $q2->where(function ($q3) use ($search) {
                          $q3->where('codigo', 'like', "%{$search}%")
                            ->orWhere('marca', 'like', "%{$search}%")
                            ->orWhere('modelo', 'like', "%{$search}%")
                            ->orWhere('numero_serie', 'like', "%{$search}%")
                            ->orWhere('codigo_patrimonial', 'like', "%{$search}%")
                            ->orWhere('ubicacion', 'like', "%{$search}%")
                            ->orWhereHas('tipoBien', function ($q4) use ($search) {
                                $q4->where('nombre', 'like', "%{$search}%");
                            });
                      });
                  });
            });
        }

        $areas = $areasQuery->orderBy('nombre')->get();

        $result = $areas->map(function ($area) use ($request) {
            $bienes = \App\Models\Bien::where('area_id', $area->id);

            if ($request->filled('estado')) {
                $bienes->where('estado', $request->estado);
            }

            if ($request->filled('tipo_bien_id')) {
                $bienes->where('tipo_bien_id', $request->tipo_bien_id);
            }

            $total = (clone $bienes)->count();
            $operativos = (clone $bienes)->where('estado', 'operativo')->count();
            $mantenimiento = (clone $bienes)->where('estado', 'mantenimiento')->count();
            $programados = (clone $bienes)->where('estado', 'programado')->count();
            $inactivos = (clone $bienes)->where('estado', 'inactivo')->count();
            $baja = (clone $bienes)->where('estado', 'baja')->count();

            $responsable = null;
            $asignacion = $area->asignaciones->first();
            if ($asignacion && $asignacion->usuario) {
                $responsable = trim($asignacion->usuario->nombres . ' ' . $asignacion->usuario->apellidos);
            }

            return [
                'id' => $area->id,
                'nombre' => $area->nombre,
                'correo' => $area->correo,
                'responsable' => $responsable,
                'total' => $total,
                'operativos' => $operativos,
                'mantenimiento' => $mantenimiento,
                'programados' => $programados,
                'inactivos' => $inactivos,
                'baja' => $baja,
            ];
        });

        return response()->json($result);
    }

    public function stats(Request $request)
    {
        $user = Auth::user();
        $rol = $user->rol->nombre;

        $query = Bien::query();

        if ($rol === 'Area Usuaria') {
            $areaIds = $user->asignaciones()->where('estado_asignacion', 'activo')->pluck('area_id');
            $query->whereIn('bienes.area_id', $areaIds);
        }

        if ($request->filled('estado')) {
            $query->where('bienes.estado', $request->estado);
        }

        if ($request->filled('tipo_bien_id')) {
            $query->where('bienes.tipo_bien_id', $request->tipo_bien_id);
        }

        if ($request->filled('area_id')) {
            $query->where('bienes.area_id', $request->area_id);
        }

        $total = (clone $query)->count();
        $operativos = (clone $query)->where('bienes.estado', 'operativo')->count();
        $mantenimiento = (clone $query)->where('bienes.estado', 'mantenimiento')->count();
        $programados = (clone $query)->where('bienes.estado', 'programado')->count();
        $inactivos = (clone $query)->where('bienes.estado', 'inactivo')->count();
        $baja = (clone $query)->where('bienes.estado', 'baja')->count();

        $porTipo = (clone $query)
            ->join('tipo_bienes', 'bienes.tipo_bien_id', '=', 'tipo_bienes.id')
            ->select('tipo_bienes.nombre', DB::raw('count(*) as total'))
            ->groupBy('tipo_bienes.nombre')
            ->get();

        return response()->json([
            'total' => $total,
            'operativos' => $operativos,
            'mantenimiento' => $mantenimiento,
            'programados' => $programados,
            'inactivos' => $inactivos,
            'baja' => $baja,
            'por_tipo' => $porTipo,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:operativo,mantenimiento,programado,inactivo,baja',
        ]);

        $bien = Bien::findOrFail($id);
        $estadoAnterior = $bien->estado;
        $bien->update(['estado' => $request->estado]);

        $this->registrarHistorial(
            $bien,
            'cambio_estado',
            "Estado cambiado de \"{$estadoAnterior}\" a \"{$request->estado}\""
        );

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'data' => $bien,
        ]);
    }

    public function historial($id)
    {
        if (!is_numeric($id)) return response()->json(['message' => 'ID inválido'], 404);
        $bien = Bien::findOrFail($id);
        $historial = $bien->historial()->orderBy('created_at', 'desc')->get();

        return response()->json($historial);
    }

    // Mantenimiento
    public function storeMantenimiento(Request $request, $bienId)
    {
        $request->validate([
            'tecnico_id' => 'nullable|exists:tecnicos,id',
            'fecha' => 'required|date',
            'tipo_mantenimiento' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'sometimes|in:completado,pendiente,en_curso',
        ]);

        $bien = Bien::findOrFail($bienId);

        $mantenimiento = MantenimientoBien::create([
            'bien_id' => $bienId,
            'tecnico_id' => $request->tecnico_id,
            'fecha' => $request->fecha,
            'tipo_mantenimiento' => $request->tipo_mantenimiento,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? 'completado',
        ]);

        $this->registrarHistorial(
            $bien,
            'mantenimiento',
            "Mantenimiento registrado: {$request->tipo_mantenimiento}"
        );

        return response()->json([
            'message' => 'Mantenimiento registrado correctamente',
            'data' => $mantenimiento->load('tecnico'),
        ], 201);
    }

    public function indexMantenimientos($bienId)
    {
        if (!is_numeric($bienId)) return response()->json(['message' => 'ID inválido'], 404);
        $bien = Bien::findOrFail($bienId);
        $mantenimientos = $bien->mantenimientos()->with('tecnico')->get();

        return response()->json($mantenimientos);
    }

    private function generarCodigo($tipoBienId, $areaId)
    {
        $tipo = \App\Models\TipoBien::find($tipoBienId);
        $area = \App\Models\Area::find($areaId);

        $prefijoTipo = 'EQP';
        if ($tipo) {
            $nombre = strtoupper($tipo->nombre);
            if (str_contains($nombre, 'ESCRITORIO')) $prefijoTipo = 'PC';
            elseif (str_contains($nombre, 'LAPTOP')) $prefijoTipo = 'LPT';
            elseif (str_contains($nombre, 'IMPRESORA')) $prefijoTipo = 'IMP';
            elseif (str_contains($nombre, 'MONITOR')) $prefijoTipo = 'MON';
            elseif (str_contains($nombre, 'TECLADO')) $prefijoTipo = 'TEC';
            elseif (str_contains($nombre, 'MOUSE')) $prefijoTipo = 'MOU';
            elseif (str_contains($nombre, 'PARLANTES')) $prefijoTipo = 'SND';
            elseif (str_contains($nombre, 'RED')) $prefijoTipo = 'NET';
            else $prefijoTipo = 'EQP';
        }

        $prefijoArea = 'GEN';
        if ($area) {
            $palabras = explode(' ', $area->nombre);
            $prefijoArea = '';
            foreach ($palabras as $palabra) {
                $prefijoArea .= strtoupper(substr($palabra, 0, 2));
            }
            $prefijoArea = substr($prefijoArea, 0, 4);
        }

        $ultimoNumero = DB::table('bienes')
            ->where('codigo', 'like', "EQP-{$prefijoArea}-%")
            ->orWhere('codigo', 'like', "{$prefijoTipo}-{$prefijoArea}-%")
            ->count();

        $numero = str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefijoTipo}-{$prefijoArea}-{$numero}";
    }

    private function registrarHistorial(Bien $bien, string $tipo, string $descripcion, ?int $usuarioId = null)
    {
        BienHistorial::create([
            'bien_id' => $bien->id,
            'tipo_evento' => $tipo,
            'descripcion' => $descripcion,
            'usuario_id' => $usuarioId ?? Auth::id(),
            'fecha' => now(),
        ]);
    }
}
