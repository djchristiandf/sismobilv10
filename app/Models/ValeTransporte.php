<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeTransporte extends Model
{
    use HasFactory;

    // Define a conexão que essa model deve utilizar
    protected $connection = 'gestaorh';

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

    // Desabilita os timestamps automáticos
    public $timestamps = false;

    protected $casts = [
        'LiberaConsulta' => 'boolean',
        'InclusaoManual' => 'boolean',
    ];
}
