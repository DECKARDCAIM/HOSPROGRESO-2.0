<?php

namespace App\Exports;

use App\Models\Disability;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DisabilityExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected array $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        if (count($this->ids) > 0) {
            return Disability::whereIn('id', $this->ids)->orderBy('id')->get();
        }
        return Disability::orderBy('id')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Discapacidad', 'Estado', 'Fecha de Creación'];
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
