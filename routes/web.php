<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValeTransporteCombustivelController;
use App\Http\Controllers\ValeTransporteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/info', function () {
    return view('info');
});

// Rota para listar todos os usuários
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Rota para mostrar o formulário de criação de um novo usuário
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

// Rota para armazenar um novo usuário
Route::post('/users', [UserController::class, 'store'])->name('users.store');

// Rota para mostrar um usuário específico
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

// Rota para mostrar o formulário de edição de um usuário específico
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');

// Rota para atualizar um usuário específico
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

// Rota para deletar um usuário específico
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// Rotas para as views
Route::get('/users/servidores/matricula/{matricula}', [UserController::class, 'getByMatriculaVwServidores'])
    ->name('users.getByMatriculaVwServidores');

Route::get('/users/usuariosacesso/matricula/{matricula}', [UserController::class, 'getByMatriculaVwUsuariosAcesso'])
    ->name('users.getByMatriculaVwUsuariosAcesso');

Route::get('/users/servidores/nome/{nome}', [UserController::class, 'getByNomeVwServidores'])
    ->name('users.getByNomeVwServidores');

Route::get('/users/usuariosacesso/nome/{nome}', [UserController::class, 'getByNomeVwUsuariosAcesso'])
    ->name('users.getByNomeVwUsuariosAcesso');

Route::get('/users/usuariosacesso/login/{login}', [UserController::class, 'getByLoginVwUsuariosAcesso'])
    ->name('users.getByLoginVwUsuariosAcesso');

Route::get('/users/servidores/login/{login}', [UserController::class, 'getByLoginVwServidores'])
    ->name('users.getByLoginVwServidores');

Route::get('/users/servidores/servidores', [UserController::class, 'getAllFromServidores'])->name('users.getAllFromServidores');
Route::get('/users/servidores/usuariosacesso', [UserController::class, 'getAllFromUsuariosAcesso'])->name('users.getAllFromUsuariosAcesso');


//valetransporte combustivel
Route::get('/valetransportecombustivel', [ValeTransporteCombustivelController::class, 'index'])
    ->name('valetransportecombustivel.index');

Route::get('/exportar-combustivel', [ValeTransporteCombustivelController::class, 'export'])->name('exportar.combustivel');
Route::get('/exportar-transporte', [ValeTransporteCombustivelController::class, 'exportXmlTransport'])->name('exportar.transporte');
Route::get('/download-xml/{filename}', function ($filename) {
    $path = storage_path("app/{$filename}");

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->download($path);
})->name('download.xml');

Route::get('/login', function() {
    return view('welcome');
})->name('login.page');

Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/api/authenticate', [UserController::class, 'authenticateApi'])->name('autentica.usuario');

Route::post('/upload-arquivo', [ValeTransporteCombustivelController::class, 'importarArquivo'])->name('importar.arquivo');

Route::get('/valetransportecombustivel/matricula/{matricula}', [ValeTransporteCombustivelController::class, 'getByMatricula'])
    ->name('valetransportecombustivel.getByMatricula');

Route::get('/valetransportecombustivel/servidor/{matricula}', [ValeTransporteCombustivelController::class, 'getByMatriculaServidores'])
    ->name('valetransportecombustivel.getByMatriculaServidores');

Route::get('/valetransportecombustivel/linha/{linha}', [ValeTransporteCombustivelController::class, 'getDadosLinha'])
    ->name('valetransportecombustivel.getDadosLinha');

Route::get('/valetransportecombustivel/nome/{nome}', [ValeTransporteCombustivelController::class, 'getByNome'])
    ->name('valetransportecombustivel.getByNome');

Route::get('/valetransportecombustivel/cpf/{cpf}', [ValeTransporteCombustivelController::class, 'getByCpf'])
    ->name('valetransportecombustivel.getByCpf');

Route::get('/valetransportecombustivel/{mes}/{ano}', [ValeTransporteCombustivelController::class, 'getByMesAno'])
    ->name('valetransportecombustivel.getByMesAno');

Route::get('/valetransportecombustivel/{mes}/{ano}/{tipo}', [ValeTransporteCombustivelController::class, 'getByMesAnoTipo'])
    ->name('valetransportecombustivel.getByMesAnoTipo');

Route::get('/valetransportecombustivel/{id}', [ValeTransporteCombustivelController::class, 'show']);

Route::post('/valetransportecombustivel/updateVale', [ValeTransporteCombustivelController::class, 'updateVale'])->name('valetransportecombustivel.updateVale');

Route::put('/valetransportecombustivel/update', [ValeTransporteCombustivelController::class, 'update'])->name('valetransportecombustivel.update');
Route::post('/valetransportecombustivel/store', [ValeTransporteCombustivelController::class, 'store'])->name('valetransportecombustivel.store');
Route::delete('/valetransportecombustivel/{id}', [ValeTransporteCombustivelController::class, 'destroy'])->name('valetransportecombustivel.destroy');

Route::get('vale-transportes', [ValeTransporteController::class, 'index'])->name('vale-transportes.index');
Route::get('vale-transportes/create', [ValeTransporteController::class, 'create'])->name('vale-transportes.create');
Route::post('vale-transportes', [ValeTransporteController::class, 'store'])->name('vale-transportes.store');
Route::get('vale-transportes/{id}', [ValeTransporteController::class, 'show'])->name('vale-transportes.show');
Route::get('vale-transportes/{id}/edit', [ValeTransporteController::class, 'edit'])->name('vale-transportes.edit');
Route::put('vale-transportes/{id}', [ValeTransporteController::class, 'update'])->name('vale-transportes.update');
Route::delete('vale-transportes/{id}', [ValeTransporteController::class, 'destroy'])->name('vale-transportes.destroy');
