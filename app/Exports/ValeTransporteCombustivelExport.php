<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ValeTransporteCombustivelExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $periodo;

    public function __construct($periodo)
    {
        $this->periodo = $periodo;
    }

    public function collection()
    {
        // Usando a conexão específica 'gestaorh' para executar a consulta
        $results = DB::connection('gestaorh')->select("SELECT * FROM [valetransp].[combustivelxlsx]('{$this->periodo}')");
        return collect($results);
    }

    public function headings(): array
    {
        return [
            'Matricula',
            'CPF',
            'Nome',
            'Linha',
            'Quantidade',
            'Valor',
            'QuantidadeExtra',
            'ValorTotal'
        ];
    }

    public function map($row): array
    {
        return [
            $row->Matricula ?? '',
            $row->CPF ?? '',
            $row->Nome ?? '',
            $row->Linha ?? '',
            $row->Quantidade ?? '',
            $row->Valor ?? '',
            $row->QuantidadeExtra ?? '',
            $row->ValorTotal ?? ''
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // Coluna Matricula
            'B' => 20,  // Coluna CPF
            'C' => 30,  // Coluna Nome
            'D' => 10,  // Coluna Linha
            'E' => 12,  // Coluna Quantidade
            'F' => 12,  // Coluna Valor
            'G' => 15,  // Coluna QuantidadeExtra
            'H' => 15,  // Coluna ValorTotal
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,               // Coluna Matricula como texto
            'B' => NumberFormat::FORMAT_TEXT,               // Coluna CPF como texto
            'C' => NumberFormat::FORMAT_TEXT,               // Coluna Nome como texto
            'D' => NumberFormat::FORMAT_TEXT,               // Coluna Linha como texto
            'E' => NumberFormat::FORMAT_NUMBER,             // Coluna Quantidade como número inteiro
            'F' => 'R$ #,##0.00',                           // Coluna Valor como moeda (R$)
            'G' => NumberFormat::FORMAT_NUMBER,             // Coluna QuantidadeExtra como número inteiro
            'H' => 'R$ #,##0.00',                           // Coluna ValorTotal como moeda (R$)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]],  // Matricula alinhado à esquerda
            'B' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]],  // CPF alinhado à esquerda
            'C' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]],  // Nome alinhado à esquerda
            'D' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]],  // Linha alinhado à esquerda
            'E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]], // Quantidade alinhado à direita
            'F' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]], // Valor alinhado à direita
            'G' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]], // QuantidadeExtra alinhado à direita
            'H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]], // ValorTotal alinhado à direita
        ];
    }
}

