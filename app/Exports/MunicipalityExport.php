<?php

namespace App\Exports;

use App\Models\Municipality;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MunicipalityExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected array $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        if (count($this->ids) > 0) {
            return Municipality::with(['department.country'])->whereIn('id', $this->ids)->orderBy('id')->get();
        }
        return Municipality::with(['department.country'])->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Municipio',
            'Departamento',
            'País',
            'Estado',
            'Fecha de Creación',
        ];
    }

    public function map($municipality): array
    {
        return [
            $municipality->id,
            $municipality->name,
            $municipality->department->name ?? '—',
            $municipality->department->country->name ?? '—',
            $municipality->is_active ? 'Activo' : 'Inactivo',
            $municipality->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
