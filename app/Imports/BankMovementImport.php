<?php

namespace App\Imports;

use App\Models\BankMovement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class BankMovementImport implements ToModel, WithHeadingRow
{
    protected $bankAccountId;

    public function __construct($bankAccountId)
    {
        $this->bankAccountId = $bankAccountId;
    }

    public function model(array $row)
    {
        // Require at least date and amount
        if (!isset($row['fecha']) || !isset($row['monto'])) {
            return null; 
        }

        // Parse Date safely
        $date = null;
        try {
            if (is_numeric($row['fecha'])) {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha']);
            } else {
                // Try from format d/m/Y or Y-m-d
                $date = Carbon::parse(str_replace('/', '-', $row['fecha']));
            }
        } catch (\Exception $e) {
            $date = Carbon::now();
        }

        $monto = (float) str_replace(',', '', $row['monto']);
        $type = strtolower(trim($row['tipo'] ?? ''));

        if (!in_array($type, ['ingreso', 'egreso'])) {
             if ($monto < 0) {
                 $type = 'egreso';
             } else {
                 $type = 'ingreso';
             }
        }

        return new BankMovement([
            'bank_account_id' => $this->bankAccountId,
            'type'            => $type,
            'amount'          => abs($monto),
            'description'     => substr(trim($row['descripcion'] ?? 'Movimiento Importado'), 0, 255),
            'date'            => $date,
            'reference'       => isset($row['referencia']) ? substr(trim($row['referencia']), 0, 255) : null,
            'reconciled'      => false,
        ]);
    }
}
