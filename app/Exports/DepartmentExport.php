<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DepartmentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected array $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        if (count($this->ids) > 0) {
            return Department::with('country')->whereIn('id', $this->ids)->orderBy('id')->get();
        }
        return Department::with('country')->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Departamento',
            'País',
            'Estado',
            'Fecha de Creación',
        ];
    }

    public function map($department): array
    {
        return [
            $department->id,
            $department->name,
            $department->country->name ?? '—',
            $department->is_active ? 'Activo' : 'Inactivo',
            $department->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
