<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ADMIN (user_id=1) ===\n";
$admin = DB::table('users')->where('id', 1)->first();
echo "  name={$admin->name} | email={$admin->email} | rol_id={$admin->rol_id} | dni={$admin->dni}\n";

echo "\n=== OTIC AREA USER (user_id=2) ===\n";
$oticUser = DB::table('users')->where('id', 2)->first();
echo "  name={$oticUser->name} | email={$oticUser->email} | rol_id={$oticUser->rol_id} | dni={$oticUser->dni}\n";

echo "\n=== ROLES ===\n";
$roles = DB::table('roles')->get();
foreach ($roles as $r) {
    echo "  id={$r->id} nombre={$r->nombre}\n";
}

echo "\n=== OTIC AREA ===\n";
$area = DB::table('areas')->where('id', 15)->first();
echo "  nombre={$area->nombre} | correo={$area->correo}\n";
