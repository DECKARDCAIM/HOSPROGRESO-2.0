<?php

namespace App\Exports;

use App\Models\Specialty;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpecialtyExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected array $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        if (count($this->ids) > 0) {
            return Specialty::whereIn('id', $this->ids)->orderBy('id')->get();
        }
        return Specialty::orderBy('id')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Especialidad', 'Estado', 'Fecha de Creación'];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->is_active ? 'Activo' : 'Inactivo',
            $item->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
