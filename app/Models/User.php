<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $connection = 'usuarios'; // Substitua 'usuarios' pela conexão desejada
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'userId';

    // Desativar o gerenciamento automático de timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email', 'name', 'isDeleted',
        'createdDtm', 'updatedDtm', 'login', 'matricula', 'setor',
        'ServidorId', 'cargo'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'isDeleted' => 'boolean', // Tratar isDeleted como booleano
    ];
}
