<?php

use Illuminate\Support\Facades\DB;

// Carregar o autoload do Composer
require 'vendor/autoload.php';

// Carregar o aplicativo Laravel
$app = require_once 'bootstrap/app.php';

// Inicializar o Kernel HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Criar uma solicitação HTTP simulada
$request = Illuminate\Http\Request::capture();
$kernel->handle($request);

// Executar a consulta para listar todos os usuários
$users = DB::table('tbl_users')->get();

// Mostrar os resultados
foreach ($users as $user) {
    echo $user->userId . ' - ' . $user->name . PHP_EOL;
}
