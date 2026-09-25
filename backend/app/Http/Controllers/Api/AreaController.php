<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\AuditHelper;
use App\Models\Area;
use App\Models\AreaUsuario;
use App\Models\User;
use App\Models\GeneralAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $query = Area::withCount('tickets')
            ->with(['asignaciones' => function ($q) {
                $q->where('estado_asignacion', 'activo')->with('usuario');
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%")
                  ->orWhereHas('asignaciones.usuario', function ($q2) use ($search) {
                      $q2->where('nombres', 'like', "%{$search}%")
                         ->orWhere('apellidos', 'like', "%{$search}%");
                  });
            });
        }

        $areas = $query->orderBy('nombre')->paginate($request->get('per_page', 100));

        return response()->json($areas);
    }

    public function stats()
    {
        $total = Area::count();
        $conResponsable = Area::whereHas('asignaciones', function ($q) {
            $q->where('estado_asignacion', 'activo');
        })->count();
        $sinResponsable = $total - $conResponsable;
        $ticketsActivos = \App\Models\Ticket::whereIn('estado', ['pendiente', 'asignado', 'en_proceso', 'en_espera'])->count();

        return response()->json([
            'total' => $total,
            'con_responsable' => $conResponsable,
            'sin_responsable' => $sinResponsable,
            'tickets_activos' => $ticketsActivos,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:areas,nombre',
            'correo' => 'required|email|max:255',
            'password_correo' => 'required|string|min:6|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|in:activo,inactivo',
        ]);

        $area = Area::create([
            'nombre' => $request->nombre,
            'correo' => strtolower($request->correo),
            'password_correo' => $request->password_correo,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? 'activo',
        ]);

        $rolArea = \App\Models\Role::where('nombre', 'Area Usuaria')->first();

        if ($rolArea) {
            User::create([
                'nombres' => $area->nombre,
                'apellidos' => '',
                'name' => $area->nombre,
                'email' => $area->correo,
                'username' => $area->correo,
                'password' => Hash::make($area->password_correo),
                'dni' => 'AREA-' . str_pad($area->id, 3, '0', STR_PAD_LEFT),
                'cargo' => 'Área Institucional',
                'rol_id' => $rolArea->id,
                'estado' => 'activo',
            ]);
        }

        GeneralAudit::create([
            'user_id' => Auth::id(),
            'rol' => AuditHelper::getRolDisplay(Auth::user()),
            'area' => '',
            'accion' => 'crear',
            'modelo' => 'Area',
            'modelo_id' => $area->id,
            'descripcion' => "Creó el área: {$area->nombre}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Área creada correctamente',
            'data' => $area,
        ], 201);
    }

    public function show($id)
    {
        $area = Area::with([
            'jefeActual.usuario',
            'tickets.createdBy',
            'tickets.assignedTo',
            'historialResponsables.usuario',
            'historialResponsables.usuarioDesignador',
            'asignaciones.usuario',
        ])->withCount('tickets')->findOrFail($id);

        return response()->json($area);
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255|unique:areas,nombre,' . $area->id,
            'correo' => 'sometimes|required|email|max:255',
            'password_correo' => 'sometimes|nullable|string|min:6|max:255',
            'descripcion' => 'sometimes|nullable|string',
            'estado' => 'sometimes|nullable|in:activo,inactivo',
        ]);

        $updateData = $request->only([
            'nombre', 'correo', 'password_correo', 'descripcion', 'estado',
        ]);

        if (isset($updateData['correo'])) {
            $updateData['correo'] = strtolower($updateData['correo']);
        }

        $area->update($updateData);

        $linkedUser = User::whereRaw('LOWER(email) = LOWER(?)', [$area->correo])
            ->orWhereRaw('LOWER(username) = LOWER(?)', [$area->correo])
            ->first();

        if ($linkedUser) {
            $updateData = ['nombres' => $area->nombre, 'name' => $area->nombre];

            if ($request->filled('correo') && strtolower($linkedUser->email) !== strtolower($request->correo)) {
                $updateData['email'] = $request->correo;
                $updateData['username'] = $request->correo;
            }

            if ($request->filled('password_correo')) {
                $updateData['password'] = Hash::make($request->password_correo);
            }

            $linkedUser->update($updateData);
        }

        GeneralAudit::create([
            'user_id' => Auth::id(),
            'rol' => AuditHelper::getRolDisplay(Auth::user()),
            'area' => '',
            'accion' => 'actualizar',
            'modelo' => 'Area',
            'modelo_id' => $area->id,
            'descripcion' => "Actualizó el área: {$area->nombre}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Área actualizada correctamente',
            'data' => $area,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        if ($area->tickets()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar el área porque tiene tickets asociados',
            ], 400);
        }

        $linkedUser = User::whereRaw('LOWER(email) = LOWER(?)', [$area->correo])
            ->orWhereRaw('LOWER(username) = LOWER(?)', [$area->correo])
            ->first();

        if ($linkedUser) {
            $linkedUser->delete();
        }

        $area->asignaciones()->delete();

        GeneralAudit::create([
            'user_id' => Auth::id(),
            'rol' => AuditHelper::getRolDisplay(Auth::user()),
            'area' => '',
            'accion' => 'eliminar',
            'modelo' => 'Area',
            'modelo_id' => $area->id,
            'descripcion' => "Eliminó el área: {$area->nombre}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $area->delete();

        return response()->json(['message' => 'Área eliminada correctamente']);
    }
}
