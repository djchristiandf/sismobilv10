<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValeTransporteCombustivel extends Model
{
    // Definir a conexão específica do banco de dados
    protected $connection = 'gestaorh';  // Nome da conexão que você definiu em `config/database.php`

    // Definir a tabela associada a este modelo
    protected $table = 'valetransp.valetransportecombustivel';

    // Se a tabela não tiver timestamps, desabilite-os
    public $timestamps = false;

    // Defina os atributos que podem ser atribuídos em massa (mass assignable)
    protected $fillable = [
        'Id', 'MesAno', 'Data', 'EmpregadoId', 'Nome', 'Matricula', 'Cpf', 'CpfM',
        'Cartao', 'Linha', 'LinhaDescricao', 'Valor', 'Quantidade', 'QuantidadeExtra',
        'LiberaConsulta', 'ValorTotal', 'InclusaoManual', 'Tipo', 'Fechada'
    ];

    // Caso a chave primária não seja 'id', defina-a explicitamente
    protected $primaryKey = 'Id';

    // Caso a chave primária não seja auto-incrementada
    public $incrementing = false;

    // Caso a chave primária não seja um integer
    protected $keyType = 'string';
}

