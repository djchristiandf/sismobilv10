<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioAcesso extends Model
{
    protected $connection = 'usuarios'; // Substitua 'usuarios' pela conexão desejada
    protected $table = 'vw_usuariosacesso'; // Define a view como a tabela do modelo
    protected $primaryKey = 'ServidorId'; // Define a chave primária

    public $timestamps = false; // Se a view não tiver colunas de timestamp

    protected $fillable = [
        'Nome', 'Login', 'Sistema', 'Matricula', 'Setor', 'Cargo', 'RoleId', 'ServidorId', 'UserId'
    ];
}
