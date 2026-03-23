<?php

namespace App\Exports;

use App\Models\Country;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CountryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected array $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        if (count($this->ids) > 0) {
            return Country::whereIn('id', $this->ids)->orderBy('id')->get();
        }
        return Country::orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'País',
            'Estado',
            'Fecha de Creación',
        ];
    }

    public function map($country): array
    {
        return [
            $country->id,
            $country->name,
            $country->is_active ? 'Activo' : 'Inactivo',
            $country->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
