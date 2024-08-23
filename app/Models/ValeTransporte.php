<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeTransporte extends Model
{
    use HasFactory;

    protected $table = 'valetransp.valetransporte';

    protected $fillable = [
        'EmpregadoId',
        'LinhaId',
        'Valor',
        'Quantidade',
        'QuantidadeExtra',
        'LiberaConsulta',
        'Data',
        'InclusaoManual',
        'Tipo'
    ];

    protected $casts = [
        'LiberaConsulta' => 'boolean',
        'InclusaoManual' => 'boolean',
    ];
}
