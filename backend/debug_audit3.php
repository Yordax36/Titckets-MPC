<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TODOS LOS AUDIT RECORDS ===\n";
$all = DB::table('general_audit')->orderBy('created_at', 'desc')->get();
foreach ($all as $a) {
    echo "id={$a->id} | modelo={$a->modelo} | modelo_id={$a->modelo_id} | user_id={$a->user_id} | accion={$a->accion} | desc={$a->descripcion}\n";
}

echo "\n=== AREAS-USUARIOS (area_usuario) ===\n";
$au = DB::table('area_usuario')->get();
foreach ($au as $a) {
    echo "area_id={$a->area_id} | usuario_id={$a->usuario_id} | activo={$a->activo}\n";
}

echo "\n=== USUARIOS CON SU AREA (via area_usuario) ===\n";
$users = DB::table('users')->get();
foreach ($users as $u) {
    $area = DB::table('area_usuario')->where('usuario_id', $u->id)->first();
    $areaName = $area ? DB::table('areas')->where('id', $area->area_id)->value('nombre') : 'sin area';
    echo "user_id={$u->id} | nombre={$u->nombres} {$u->apellidos} | area={$areaName}\n";
}
