<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Country;
use App\Models\Department;
use App\Models\Municipality;
use App\Models\Gender;
use App\Models\CivilStatus;
use App\Models\Ethnicity;
use App\Models\LinguisticCommunity;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PatientsExport;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Patient::with([
            'gender',
            'civilStatus',
            'ethnicity',
            'linguisticCommunity',
            'country',
            'department',
            'municipality'
        ]);

        // Aplicar filtros de búsqueda
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('gender_id')) {
            $query->byGender($request->gender_id);
        }

        if ($request->filled('civil_status_id')) {
            $query->byCivilStatus($request->civil_status_id);
        }

        if ($request->filled('ethnicity_id')) {
            $query->byEthnicity($request->ethnicity_id);
        }

        if ($request->filled('linguistic_community_id')) {
            $query->byLinguisticCommunity($request->linguistic_community_id);
        }

        if ($request->filled('country_id')) {
            $query->byCountry($request->country_id);
        }

        if ($request->filled('department_id')) {
            $query->byDepartment($request->department_id);
        }

        if ($request->filled('municipality_id')) {
            $query->byMunicipality($request->municipality_id);
        }

        // Filtro por estado (activos/inactivos)
        if ($request->filled('status')) {
            if ($request->status === 'inactive') {
                $query->onlyTrashed();
            } else {
                $query->withoutTrashed();
            }
        } else {
            // Por defecto mostrar solo activos
            $query->withoutTrashed();
        }

        // Filtro por fecha de nacimiento
        if ($request->filled('birth_date_from')) {
            $query->whereDate('birth_date', '>=', $request->birth_date_from);
        }
        if ($request->filled('birth_date_to')) {
            $query->whereDate('birth_date', '<=', $request->birth_date_to);
        }

        $patients = $query->latest()->paginate(15);

        // Estadísticas
        $totalPatients = Patient::count();
        $activePatients = Patient::count(); // Todos están activos si no están eliminados
        $todayPatients = Patient::whereDate('created_at', today())->count();
        
        // Estadísticas por género
        $maleCount = Patient::whereHas('gender', function($q) {
            $q->where('code', 'M');
        })->count();
        $femaleCount = Patient::whereHas('gender', function($q) {
            $q->where('code', 'F');
        })->count();
        $genderPercentage = $totalPatients > 0 ? round(($maleCount / $totalPatients) * 100, 1) : 0;

        // Cargar catálogos para filtros
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        
        // Cargar departamentos según país seleccionado
        if ($request->filled('country_id')) {
            $departments = Department::where('country_id', $request->country_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            $departments = collect();
        }
        
        // Cargar municipios según departamento seleccionado
        if ($request->filled('department_id')) {
            $municipalities = Municipality::where('department_id', $request->department_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            $municipalities = collect();
        }
        $genders = Gender::where('is_active', true)->orderBy('name')->get();
        $civilStatuses = CivilStatus::where('is_active', true)->orderBy('name')->get();
        $ethnicities = Ethnicity::where('is_active', true)->orderBy('name')->get();
        $linguisticCommunities = LinguisticCommunity::where('is_active', true)->orderBy('name')->get();

        return view('modules.patient.index', compact(
            'patients',
            'countries',
            'departments',
            'municipalities',
            'genders',
            'civilStatuses',
            'ethnicities',
            'linguisticCommunities',
            'totalPatients',
            'activePatients',
            'todayPatients',
            'genderPercentage'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cargar todos los catálogos necesarios
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $municipalities = Municipality::where('is_active', true)->orderBy('name')->get();
        $genders = Gender::where('is_active', true)->orderBy('name')->get();
        $civilStatuses = CivilStatus::where('is_active', true)->orderBy('name')->get();
        $ethnicities = Ethnicity::where('is_active', true)->orderBy('name')->get();
        $linguisticCommunities = LinguisticCommunity::where('is_active', true)->orderBy('name')->get();

        return view('modules.patient.create', compact(
            'countries',
            'departments',
            'municipalities',
            'genders',
            'civilStatuses',
            'ethnicities',
            'linguisticCommunities'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();

        // Calcular edad si se proporciona fecha de nacimiento
        if (isset($validated['birth_date'])) {
            $birthDate = Carbon::parse($validated['birth_date']);
            $age = $birthDate->age;
            
            // Si es menor de edad, validar que los datos de la madre estén presentes
            if ($age < 18) {
                if (empty($validated['mother_first_name']) || empty($validated['mother_first_last_name'])) {
                    return back()->withErrors([
                        'birth_date' => 'Para menores de edad, los datos de la madre son obligatorios.'
                    ])->withInput();
                }
            }
        }

        $patient = Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Paciente registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient->load([
            'gender',
            'civilStatus',
            'ethnicity',
            'linguisticCommunity',
            'country',
            'department',
            'municipality'
        ]);

        return view('modules.patient.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        // Cargar todos los catálogos necesarios
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        
        // Cargar departamentos según el país del paciente
        $departments = $patient->country 
            ? Department::where('country_id', $patient->country_id)->where('is_active', true)->orderBy('name')->get()
            : collect();
        
        // Cargar municipios según el departamento del paciente
        $municipalities = $patient->department 
            ? Municipality::where('department_id', $patient->department_id)->where('is_active', true)->orderBy('name')->get()
            : collect();
        
        $genders = Gender::where('is_active', true)->orderBy('name')->get();
        $civilStatuses = CivilStatus::where('is_active', true)->orderBy('name')->get();
        $ethnicities = Ethnicity::where('is_active', true)->orderBy('name')->get();
        $linguisticCommunities = LinguisticCommunity::where('is_active', true)->orderBy('name')->get();

        return view('modules.patient.edit', compact(
            'patient',
            'countries',
            'departments',
            'municipalities',
            'genders',
            'civilStatuses',
            'ethnicities',
            'linguisticCommunities'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $validated = $request->validated();

        // Calcular edad si se proporciona fecha de nacimiento
        if (isset($validated['birth_date'])) {
            $birthDate = Carbon::parse($validated['birth_date']);
            $age = $birthDate->age;
            
            // Si es menor de edad, validar que los datos de la madre estén presentes
            if ($age < 18) {
                if (empty($validated['mother_first_name']) || empty($validated['mother_first_last_name'])) {
                    return back()->withErrors([
                        'birth_date' => 'Para menores de edad, los datos de la madre son obligatorios.'
                    ])->withInput();
                }
            }
        }

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Paciente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete(); // Soft delete

        return redirect()->route('patients.index')
            ->with('success', 'Paciente eliminado exitosamente.');
    }

    /**
     * Eliminar múltiples pacientes
     */
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:patients,id',
        ]);

        Patient::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pacientes eliminados exitosamente.'
        ]);
    }

    /**
     * Reactivar paciente
     */
    public function restore(Request $request, $id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('patients.index')
            ->with('success', 'Paciente reactivado exitosamente.');
    }

    /**
     * Exportar a Excel
     */
    public function exportExcel(Request $request)
    {
        $query = $this->buildExportQuery($request);
        $patients = $query->get();

        $filename = 'pacientes_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new PatientsExport($patients), $filename);
    }

    /**
     * Exportar a CSV
     */
    public function exportCSV(Request $request)
    {
        $query = $this->buildExportQuery($request);
        $patients = $query->get();

        $filename = 'pacientes_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($patients) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'ID',
                'Nombre Completo',
                'DPI',
                'Fecha de Nacimiento',
                'Edad',
                'Género',
                'Estado Civil',
                'Etnia',
                'Comunidad Lingüística',
                'Escolaridad',
                'Ocupación',
                'Departamento',
                'Municipio',
                'Lugar',
                'Fecha de Registro'
            ], ';');

            // Data
            foreach ($patients as $patient) {
                fputcsv($file, [
                    $patient->id,
                    $patient->full_name,
                    $patient->dpi ?: '',
                    $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '',
                    $patient->age ?: '',
                    $patient->gender ? $patient->gender->name : '',
                    $patient->civilStatus ? $patient->civilStatus->name : '',
                    $patient->ethnicity ? $patient->ethnicity->name : '',
                    $patient->linguisticCommunity ? $patient->linguisticCommunity->name : '',
                    $patient->education ?: '',
                    $patient->occupation ?: '',
                    $patient->department ? $patient->department->name : '',
                    $patient->municipality ? $patient->municipality->name : '',
                    $patient->place ?: '',
                    $patient->created_at->format('d/m/Y H:i')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar a PDF
     */
    public function exportPDF(Request $request)
    {
        $query = $this->buildExportQuery($request);
        $patients = $query->get();

        $html = view('modules.patient.export-pdf', compact('patients'))->render();
        
        $pdf = \PDF::loadHTML($html);
        $filename = 'pacientes_' . date('Y-m-d_His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Construir query para exportación
     */
    private function buildExportQuery(Request $request)
    {
        $query = Patient::with([
            'gender',
            'civilStatus',
            'ethnicity',
            'linguisticCommunity',
            'country',
            'department',
            'municipality'
        ]);

        // Aplicar mismos filtros que en index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('gender_id')) {
            $query->byGender($request->gender_id);
        }

        if ($request->filled('civil_status_id')) {
            $query->byCivilStatus($request->civil_status_id);
        }

        if ($request->filled('ethnicity_id')) {
            $query->byEthnicity($request->ethnicity_id);
        }

        if ($request->filled('linguistic_community_id')) {
            $query->byLinguisticCommunity($request->linguistic_community_id);
        }

        if ($request->filled('country_id')) {
            $query->byCountry($request->country_id);
        }

        if ($request->filled('department_id')) {
            $query->byDepartment($request->department_id);
        }

        if ($request->filled('municipality_id')) {
            $query->byMunicipality($request->municipality_id);
        }

        // Si hay IDs específicos seleccionados
        if ($request->has('ids') && is_array($request->ids) && count($request->ids) > 0) {
            $query->whereIn('id', $request->ids);
        }

        return $query->latest();
    }

    /**
     * Obtener departamentos por país (para AJAX)
     */
    public function getDepartmentsByCountry(Request $request)
    {
        $countryId = $request->input('country_id');
        
        $departments = Department::where('country_id', $countryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($departments);
    }

    /**
     * Obtener municipios por departamento (para AJAX)
     */
    public function getMunicipalitiesByDepartment(Request $request)
    {
        $departmentId = $request->input('department_id');
        
        $municipalities = Municipality::where('department_id', $departmentId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($municipalities);
    }
}