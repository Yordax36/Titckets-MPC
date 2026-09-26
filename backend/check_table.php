<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$result = DB::select('SHOW CREATE TABLE area_usuario');
print_r($result);