<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FinancialExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;
    protected $type;

    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        if ($this->type === 'invoices') {
            return ['ID', 'Paciente', 'N° Doc', 'Total', 'Deuda', 'Vencimiento', 'Estado', 'Fecha Emisión'];
        }
        elseif ($this->type === 'cash') {
            return ['ID', 'Cajero', 'Tipo', 'Monto', 'Categoría', 'Referencia', 'Fecha'];
        }
        elseif ($this->type === 'bank_movements') {
            return ['ID', 'Cuenta Bancaria', 'Tipo', 'Monto', 'Operación / Referencia', 'Descripción', 'Fecha'];
        }
        elseif ($this->type === 'work_orders') {
            return ['ID', 'Paciente', 'Doctor', 'Técnico', 'Monto', 'Estado', 'Fecha Creación'];
        }

        return [];
    }

    public function map($row): array
    {
        if ($this->type === 'invoices') {
            return [
                '#OT-' . str_pad($row->id, 4, '0', STR_PAD_LEFT),
                $row->patient->name ?? 'N/A',
                $row->series . '-' . $row->number,
                'S/ ' . number_format($row->total, 2),
                'S/ ' . number_format($row->total - $row->payments()->sum('amount'), 2),
                $row->due_date ? $row->due_date->format('d/m/Y') : '',
                strtoupper($row->status),
                $row->created_at->format('d/m/Y H:i')
            ];
        }
        elseif ($this->type === 'cash') {
            return [
                $row->id,
                $row->user->name ?? 'Sistema',
                strtoupper($row->type),
                'S/ ' . number_format($row->amount, 2),
                $row->category->name ?? 'N/A',
                $row->ref_doc,
                $row->date ?\Carbon\Carbon::parse($row->date)->format('d/m/Y') : ''
            ];
        }
        elseif ($this->type === 'bank_movements') {
            return [
                $row->id,
                $row->account->name ?? 'N/A',
                strtoupper($row->type),
                'S/ ' . number_format($row->amount, 2),
                $row->reference,
                $row->description,
                $row->date ? $row->date->format('d/m/Y') : ''
            ];
        }
        elseif ($this->type === 'work_orders') {
            return [
                '#OT-' . str_pad($row->id, 4, '0', STR_PAD_LEFT),
                $row->patient->name ?? 'N/A',
                $row->doctor->name ?? 'N/A',
                $row->technician->name ?? 'N/A',
                'S/ ' . number_format($row->total, 2),
                strtoupper($row->status),
                $row->created_at->format('d/m/Y H:i')
            ];
        }

        return [];
    }
}
