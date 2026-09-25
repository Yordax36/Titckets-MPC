<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);

    // Protected routes
    Route::prefix('auth')->middleware('jwt')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::post('/refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);
        Route::get('/me', [\App\Http\Controllers\Api\AuthController::class, 'me']);
    });

    // Protected API routes
    Route::middleware('jwt')->group(function () {

        // Usuarios — specific routes BEFORE resource
        Route::get('usuarios/tecnicos', [\App\Http\Controllers\Api\UsuarioController::class, 'tecnicos']);
        Route::apiResource('usuarios', \App\Http\Controllers\Api\UsuarioController::class);
        Route::put('usuarios/{id}/estado', [\App\Http\Controllers\Api\UsuarioController::class, 'toggleEstado']);

        // Cargos
        Route::get('cargos/all', [\App\Http\Controllers\Api\CargoController::class, 'all']);
        Route::apiResource('cargos', \App\Http\Controllers\Api\CargoController::class);

        // Designaciones (antes Asignaciones)
        Route::get('designaciones/disponibles/areas', [\App\Http\Controllers\Api\DesignacionController::class, 'areasDisponibles']);
        Route::get('designaciones/disponibles/usuarios', [\App\Http\Controllers\Api\DesignacionController::class, 'usuariosDisponibles']);
        Route::get('designaciones/disponibles/cargos', [\App\Http\Controllers\Api\DesignacionController::class, 'cargosDisponibles']);
        Route::put('designaciones/{id}/finalizar', [\App\Http\Controllers\Api\DesignacionController::class, 'finalizar']);
        Route::put('designaciones/{id}', [\App\Http\Controllers\Api\DesignacionController::class, 'update']);
        Route::get('designaciones/historial/{areaId}', [\App\Http\Controllers\Api\DesignacionController::class, 'historial']);
        Route::get('designaciones/historial-personal/{usuarioId}', [\App\Http\Controllers\Api\DesignacionController::class, 'historialPersonal']);
        Route::apiResource('designaciones', \App\Http\Controllers\Api\DesignacionController::class)->except(['update']);

        Route::get('areas/stats', [\App\Http\Controllers\Api\AreaController::class, 'stats']);
        Route::apiResource('areas', \App\Http\Controllers\Api\AreaController::class);

        // Técnicos de Mesa de Ayuda
        Route::get('tecnicos/all', [\App\Http\Controllers\Api\TecnicoController::class, 'all']);
        Route::get('tecnicos/stats', [\App\Http\Controllers\Api\TecnicoController::class, 'stats']);
        Route::get('tecnicos/{id}/historial', [\App\Http\Controllers\Api\TecnicoController::class, 'historial']);
        Route::get('tecnicos/{id}/stats-detalle', [\App\Http\Controllers\Api\TecnicoController::class, 'statsDetalle']);
        Route::put('tecnicos/{id}/estado', [\App\Http\Controllers\Api\TecnicoController::class, 'toggleEstado']);
        Route::put('tecnicos/{id}/reset-password', [\App\Http\Controllers\Api\TecnicoController::class, 'resetPassword']);
        Route::apiResource('tecnicos', \App\Http\Controllers\Api\TecnicoController::class);

        // Tickets — specific routes BEFORE resource
        Route::get('tickets/stats', [\App\Http\Controllers\Api\TicketController::class, 'stats']);
        Route::apiResource('tickets', \App\Http\Controllers\Api\TicketController::class)->except(['destroy']);
        Route::put('tickets/{id}/estado', [\App\Http\Controllers\Api\TicketController::class, 'cambiarEstado']);
        Route::put('tickets/{id}/asignar', [\App\Http\Controllers\Api\TicketController::class, 'asignarTecnico']);
        Route::get('tickets/{id}/pdf', [\App\Http\Controllers\Api\TicketPdfController::class, 'generate']);
        Route::get('tickets/{id}/historial', [\App\Http\Controllers\Api\TicketController::class, 'historial']);
        Route::post('tickets/{id}/evidencia', [\App\Http\Controllers\Api\TicketController::class, 'uploadEvidencia']);
        Route::delete('tickets/{ticketId}/evidencia/{evidenciaId}', [\App\Http\Controllers\Api\TicketController::class, 'deleteEvidencia']);

        Route::get('tickets/{ticketId}/respuestas', [\App\Http\Controllers\Api\RespuestaController::class, 'index']);
        Route::post('tickets/{ticketId}/respuestas', [\App\Http\Controllers\Api\RespuestaController::class, 'store']);

        Route::get('dashboard/estadisticas', [\App\Http\Controllers\Api\DashboardController::class, 'estadisticas']);
        Route::get('dashboard/recientes', [\App\Http\Controllers\Api\DashboardController::class, 'ticketsRecientes']);

        Route::get('roles', [\App\Http\Controllers\Api\RoleController::class, 'index']);
        Route::get('permisos', [\App\Http\Controllers\Api\PermisoController::class, 'index']);

        Route::get('auditoria', [\App\Http\Controllers\Api\AuditController::class, 'index']);

        // DNI Lookup
        Route::post('dni/lookup', [\App\Http\Controllers\Api\DniController::class, 'lookup']);

        // Settings
        Route::put('settings', [\App\Http\Controllers\Api\SettingsController::class, 'update']);

        // Area Profile
        Route::get('area-profile', [\App\Http\Controllers\Api\AreaProfileController::class, 'profile']);
        Route::put('area-profile/password', [\App\Http\Controllers\Api\AreaProfileController::class, 'changePassword']);

        // Tipos de Bienes
        Route::get('tipos-bienes/all', [\App\Http\Controllers\Api\TipoBienController::class, 'all']);
        Route::apiResource('tipos-bienes', \App\Http\Controllers\Api\TipoBienController::class)->except(['show']);

        // Bienes
        Route::get('bienes/stats', [\App\Http\Controllers\Api\BienController::class, 'stats']);
        Route::get('bienes/por-area', [\App\Http\Controllers\Api\BienController::class, 'porArea']);
        Route::put('bienes/{id}/estado', [\App\Http\Controllers\Api\BienController::class, 'cambiarEstado']);
        Route::get('bienes/{id}/historial', [\App\Http\Controllers\Api\BienController::class, 'historial']);
        Route::get('bienes/{bienId}/mantenimientos', [\App\Http\Controllers\Api\BienController::class, 'indexMantenimientos']);
        Route::post('bienes/{bienId}/mantenimientos', [\App\Http\Controllers\Api\BienController::class, 'storeMantenimiento']);
        Route::apiResource('bienes', \App\Http\Controllers\Api\BienController::class);
    });
});
