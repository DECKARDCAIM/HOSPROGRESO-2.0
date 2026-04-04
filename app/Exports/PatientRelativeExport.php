<?php

namespace App\Exports;

use App\Models\PatientRelative;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientRelativeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected array $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $query = PatientRelative::with(['patient', 'relationshipType']);
        if (count($this->ids) > 0) {
            return $query->whereIn('id', $this->ids)->orderBy('id')->get();
        }
        return $query->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre Completo',
            'CUI',
            'Paciente',
            'Relación',
            'Fecha de Creación',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->first_name . ' ' . $item->first_last_name,
            $item->cui,
            $item->patient->first_name . ' ' . $item->patient->first_last_name,
            $item->relationshipType->name ?? 'N/A',
            $item->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
