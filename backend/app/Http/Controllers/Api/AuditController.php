<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralAudit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = GeneralAudit::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->has('modelo')) {
            $query->where('modelo', $request->modelo);
        }

        if ($request->has('modelo_id')) {
            $query->where('modelo_id', $request->modelo_id);
        }

        if ($request->has('area_id')) {
            $areaId = $request->area_id;
            $area = \App\Models\Area::find($areaId);
            $userIdsInArea = \App\Models\AreaUsuario::where('area_id', $areaId)
                ->pluck('usuario_id')
                ->toArray();
            if ($area) {
                $institucionalUser = \App\Models\User::where('email', $area->correo)->first();
                if ($institucionalUser && !in_array($institucionalUser->id, $userIdsInArea)) {
                    $userIdsInArea[] = $institucionalUser->id;
                }
            }

            $query->where(function ($q) use ($areaId, $userIdsInArea) {
                $q->where('modelo', 'Area')->where('modelo_id', $areaId);
                if (!empty($userIdsInArea)) {
                    $q->orWhereIn('user_id', $userIdsInArea);
                    $q->orWhere(function ($q2) use ($userIdsInArea) {
                        $q2->where('modelo', 'User')->whereIn('modelo_id', $userIdsInArea);
                    });
                }
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('descripcion', 'like', "%{$search}%")
                  ->orWhere('accion', 'like', "%{$search}%");
            });
        }

        if ($request->has('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $auditorias = $query->orderBy('created_at', 'desc')->paginate(50);

        return response()->json($auditorias);
    }
}
