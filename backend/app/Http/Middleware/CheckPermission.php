<?php

namespace App\Http\Middleware;

use App\Services\PermisoService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    protected PermisoService $permisoService;

    public function __construct(PermisoService $permisoService)
    {
        $this->permisoService = $permisoService;
    }

    public function handle(Request $request, Closure $next, string $permiso): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        if (!$this->permisoService->userHasPermission($user, $permiso)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return $next($request);
    }
}
