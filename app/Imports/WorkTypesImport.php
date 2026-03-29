<?php
namespace App\Imports;

use App\Models\WorkType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class WorkTypesImport implements ToModel, WithStartRow
{
    public function startRow(): int { return 2; }
    public function model(array $row)
    {
        if (!isset($row[4]) || trim((string)$row[4]) === '') return null;
        $price = isset($row[5]) ? preg_replace('/[^0-9.]/', '', str_replace(',', '.', $row[5])) : 0;
        $workType = new WorkType();
        $workType->name = trim((string)$row[4]);
        $workType->description = isset($row[3]) && trim((string)$row[3]) !== '' ? 'Categoría: ' . trim((string)$row[3]) : null;
        $workType->default_price = is_numeric($price) && $price !== '' ? (float) $price : 0.00;
        return $workType;
    }
}