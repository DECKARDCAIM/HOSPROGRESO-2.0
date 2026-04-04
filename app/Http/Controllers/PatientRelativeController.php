<?php

namespace App\Http\Controllers;

use App\Exports\PatientRelativeExport;
use App\Http\Requests\StorePatientRelativeRequest;
use App\Http\Requests\UpdatePatientRelativeRequest;
use App\Models\Patient;
use App\Models\PatientRelative;
use App\Models\RelationshipType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class PatientRelativeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'patient_relatives_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['patient_relatives', 'patients', 'relationship_types'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = PatientRelative::with(['patient', 'relationshipType']);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('first_name', 'LIKE', "%{$request->search}%")
                        ->orWhere('first_last_name', 'LIKE', "%{$request->search}%")
                        ->orWhere('cui', 'LIKE', "%{$request->search}%");
                });
            }

            $perPage = $request->input('per_page', 25);

            $allFilteredIds = (clone $query)->pluck('id')->toArray();
            $patientRelatives = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $totalCount = PatientRelative::count();

            return compact('patientRelatives', 'allFilteredIds', 'totalCount');
        });

        return view('modules.maintenance.patient-relatives.index', $data);
    }

    public function create()
    {
        $data = $this->getCachedCatalogs();

        return view('modules.maintenance.patient-relatives.create', $data);
    }

    public function store(StorePatientRelativeRequest $request)
    {
        $item = PatientRelative::create($request->validated());

        $notification = [
            'message' => 'El familiar '.$item->first_name.' se ha registrado correctamente.',
            'alert-type' => 'success',
        ];

        return redirect()->route('patient-relatives.index')->with(compact('notification'));
    }

    public function show(PatientRelative $patientRelative) {}

    public function edit(PatientRelative $patientRelative)
    {
        $data = $this->getCachedCatalogs();
        $data['patientRelative'] = $patientRelative;

        return view('modules.maintenance.patient-relatives.edit', $data);
    }

    public function update(UpdatePatientRelativeRequest $request, PatientRelative $patientRelative)
    {
        $patientRelative->update($request->validated());

        $notification = [
            'message' => 'El familiar '.$patientRelative->first_name.' se ha actualizado correctamente.',
            'alert-type' => 'info',
        ];

        return redirect()->route('patient-relatives.index')->with(compact('notification'));
    }

    public function destroy(PatientRelative $patientRelative)
    {
        $patientRelative->delete();

        $notification = [
            'message' => 'El familiar '.$patientRelative->first_name.' ha sido eliminado correctamente.',
            'alert-type' => 'warning',
        ];

        return redirect()->route('patient-relatives.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        PatientRelative::whereIn('id', $ids)->delete();

        // Flush manual necesario para actualizaciones/borrados masivos
        Cache::tags(['patient_relatives'])->flush();

        return back()->with('success', count($ids).' familiares han sido eliminados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new PatientRelativeExport($ids), 'familiares_pacientes.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new PatientRelativeExport($ids), 'familiares_pacientes.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0
            ? PatientRelative::with(['patient', 'relationshipType'])->whereIn('id', $ids)->orderBy('id')->get()
            : PatientRelative::with(['patient', 'relationshipType'])->orderBy('id')->get();

        $pdf = Pdf::loadView('modules.maintenance.patient-relatives.print', compact('items'));

        return $pdf->download('familiares_pacientes.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0
            ? PatientRelative::with(['patient', 'relationshipType'])->whereIn('id', $ids)->orderBy('id')->get()
            : PatientRelative::with(['patient', 'relationshipType'])->orderBy('id')->get();

        return view('modules.maintenance.patient-relatives.print', compact('items'));
    }

    /**
     * Cacheamos los catálogos para evitar consultas pesadas al abrir los formularios
     */
    private function getCachedCatalogs()
    {
        return [
            'patients' => Cache::tags(['patients'])->remember('active_patients_list', now()->addHours(2), fn () => Patient::where('is_active', true)->orderBy('first_name')->get()),
            'relationshipTypes' => Cache::tags(['relationship_types'])->remember('active_relationship_types', now()->addDays(1), fn () => RelationshipType::where('is_active', true)->orderBy('name')->get()),
        ];
    }
}
