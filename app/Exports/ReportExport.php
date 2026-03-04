<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function __construct(private array $data) {}

    public function headings(): array
    {
        return $this->data['columns'] ?? [];
    }

    public function array(): array
    {
        return $this->data['rows'] ?? [];
    }
}