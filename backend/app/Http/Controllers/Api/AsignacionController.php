<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\AuditHelper;
use App\Models\AreaUsuario;
use App\Models\Area;
use App\Models\User;
use App\Models\GeneralAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsignacionController extends Controller
{
    public function index(Request $request)
    {
        $query = AreaUsuario::with(['area', 'usuario' => function ($q) {
            $q->with('rol');
        }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('area', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            })->orWhereHas('usuario', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado_asignacion', $request->estado);
        }

        $asignaciones = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($asignaciones);
    }

    public function store(Request $request)
    {
        $rol = Auth::user()->rol;
        if ($rol->nombre !== 'Administrador') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'usuario_id' => 'required|exists:users,id',
            'cargo' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
        ]);

        $asignacionActiva = AreaUsuario::where('area_id', $request->area_id)
            ->where('estado_asignacion', 'activo')
            ->first();

        if ($asignacionActiva) {
            return response()->json([
                'message' => 'El área ya tiene un responsable activo. Finaliza la asignación actual primero.',
            ], 422);
        }

        $area = Area::findOrFail($request->area_id);
        $usuario = User::findOrFail($request->usuario_id);

        $asignacion = AreaUsuario::create([
            'area_id' => $request->area_id,
            'usuario_id' => $request->usuario_id,
            'cargo' => $request->cargo,
            'observaciones' => $request->observaciones,
            'fecha_asignacion' => $request->fecha_inicio,
            'fecha_inicio' => $request->fecha_inicio,
            'activo' => true,
            'estado_asignacion' => 'activo',
        ]);

        GeneralAudit::create([
            'user_id' => Auth::id(),
            'rol' => AuditHelper::getRolDisplay(Auth::user()),
            'area' => $area->nombre,
            'accion' => 'crear',
            'modelo' => 'AreaUsuario',
            'modelo_id' => $asignacion->id,
            'descripcion' => "Asignó a {$usuario->nombres} {$usuario->apellidos}" . ($request->cargo ? " como {$request->cargo}" : '') . " del área: {$area->nombre}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Asignación creada correctamente',
            'data' => $asignacion->load(['area', 'usuario']),
        ], 201);
    }

    public function show($id)
    {
        $asignacion = AreaUsuario::with(['area', 'usuario' => function ($q) {
            $q->with('rol');
        }])->findOrFail($id);

        return response()->json($asignacion);
    }

    public function update(Request $request, $id)
    {
        $rol = Auth::user()->rol;
        if ($rol->nombre !== 'Administrador') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $asignacion = AreaUsuario::findOrFail($id);

        $request->validate([
            'cargo' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $asignacion->update($request->only(['cargo', 'observaciones']));
        $asignacion->load(['area', 'usuario']);

        GeneralAudit::create([
            'user_id' => Auth::id(),
            'rol' => AuditHelper::getRolDisplay(Auth::user()),
            'area' => $asignacion->area->nombre ?? '',
            'accion' => 'actualizar',
            'modelo' => 'AreaUsuario',
            'modelo_id' => $asignacion->id,
            'descripcion' => "Actualizó asignación del área: {$asignacion->area->nombre}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Asignación actualizada correctamente',
            'data' => $asignacion,
        ]);
    }

    public function finalizar(Request $request, $id)
    {
        $rol = Auth::user()->rol;
        if ($rol->nombre !== 'Administrador') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $asignacion = AreaUsuario::findOrFail($id);

        if ($asignacion->estado_asignacion !== 'activo') {
            return response()->json([
                'message' => 'Esta asignación ya fue finalizada.',
            ], 422);
        }

        $asignacion->update([
            'fecha_fin' => $request->fecha_fin,
            'estado_asignacion' => 'finalizado',
            'activo' => false,
        ]);

        $asignacion->load(['area', 'usuario']);

        GeneralAudit::create([
            'user_id' => Auth::id(),
            'rol' => AuditHelper::getRolDisplay(Auth::user()),
            'area' => $asignacion->area->nombre ?? '',
            'accion' => 'finalizar',
            'modelo' => 'AreaUsuario',
            'modelo_id' => $asignacion->id,
            'descripcion' => "Finalizó funciones de {$asignacion->usuario->nombres} {$asignacion->usuario->apellidos} en el área: {$asignacion->area->nombre}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Asignación finalizada correctamente',
            'data' => $asignacion,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        return response()->json([
            'message' => 'No se pueden eliminar asignaciones. Use la opción de finalizar.',
        ], 400);
    }

    public function historial($areaId)
    {
        $historial = AreaUsuario::with(['usuario' => function ($q) {
            $q->with('rol');
        }])
            ->where('area_id', $areaId)
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return response()->json($historial);
    }

    public function areasDisponibles()
    {
        $areas = Area::where('estado', 'activo')->orderBy('nombre')->get();
        return response()->json($areas);
    }

    public function usuariosDisponibles()
    {
        $usuarios = User::where('estado', 'activo')
            ->whereHas('rol', function ($q) {
                $q->where('nombre', '!=', 'Administrador')->where('nombre', '!=', 'Area Usuaria');
            })
            ->orderBy('nombres')
            ->get();

        return response()->json($usuarios);
    }
}
