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
use App\Models\RelationshipType;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Patient::with([
            'clinicalRecord',
            'gender',
            'civilStatus',
            'ethnicity',
            'linguisticCommunity',
            'country',
            'department',
            'municipality',
            'relatives'
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

        $perPage = $request->get('per_page', 25);
        $patients = $query->latest()->paginate($perPage)->appends($request->query());

        // Estadísticas
        $totalPatients = Patient::count();
        $todayPatients = Patient::whereDate('created_at', today())->count();

        // Estadísticas por género
        $genderStats = Patient::selectRaw('gender_id, COUNT(*) as total')
            ->groupBy('gender_id')
            ->pluck('total','gender_id');
        $maleCount = $genderStats[1] ?? 0;
        $femaleCount = $genderStats[2] ?? 0;
        $malePercentage = $totalPatients > 0
            ? round(($maleCount / $totalPatients) * 100, 1)
            : 0;
        $femalePercentage = $totalPatients > 0
            ? round(($femaleCount / $totalPatients) * 100, 1)
            : 0;

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
            'todayPatients',
            'malePercentage',
            'femalePercentage'
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

        $relationshipTypes = RelationshipType::where('is_active', true)->orderBy('name')->get();

        return view('modules.patient.create', compact(
            'countries',
            'departments',
            'municipalities',
            'genders',
            'civilStatuses',
            'ethnicities',
            'linguisticCommunities',
            'relationshipTypes'
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
        }

        // Se usa una transacción para asegurar que ambos registros se creen exitosamente
        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
            $patient = Patient::create($validated);
            
            // Generar número de expediente automático: EXP-AÑO-MES-CORRELATIVO
            $year = date('Y');
            $month = date('m');
            
            // Buscar el último correlativo de ese mes y año
            $lastRecord = \App\Models\ClinicalRecord::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('id', 'desc')
                ->first();
                
            $correlative = 1;
            if ($lastRecord) {
                // Extraer el correlativo actual y sumarle 1
                $parts = explode('-', $lastRecord->record_number);
                if (count($parts) == 4) {
                    $correlative = intval($parts[3]) + 1;
                } else {
                    $correlative = \App\Models\ClinicalRecord::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
                }
            }
            
            $paddedCorrelative = str_pad($correlative, 4, '0', STR_PAD_LEFT);
            $newRecordNumber = "EXP-{$year}-{$month}-{$paddedCorrelative}";
            
            $patient->clinicalRecord()->create([
                'record_number' => $newRecordNumber
            ]);
            
            // Guardar familiares dinámicos
            if ($request->has('relatives') && is_array($request->input('relatives'))) {
                foreach ($request->input('relatives') as $relativeData) {
                    $patient->relatives()->create([
                        'relationship_type_id' => $relativeData['relationship_type_id'],
                        'first_name' => $relativeData['first_name'],
                        'second_name' => $relativeData['second_name'] ?? null,
                        'third_name' => $relativeData['third_name'] ?? null,
                        'first_last_name' => $relativeData['first_last_name'],
                        'second_last_name' => $relativeData['second_last_name'] ?? null,
                        'married_last_name' => $relativeData['married_last_name'] ?? null,
                        'dpi' => $relativeData['dpi'] ?? null,
                    ]);
                }
            }
        });

        $notification = [
            'message' => 'Paciente registrado exitosamente.',
            'alert-type' => 'success'
        ];

        return redirect()->route('patients.index')
            ->with('notification', $notification);
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

        $relationshipTypes = RelationshipType::where('is_active', true)->orderBy('name')->get();

        return view('modules.patient.edit', compact(
            'patient',
            'countries',
            'departments',
            'municipalities',
            'genders',
            'civilStatuses',
            'ethnicities',
            'linguisticCommunities',
            'relationshipTypes'
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
        }

        $patient->update($validated);

        $notification = [
            'message' => 'Paciente actualizado exitosamente.',
            'alert-type' => 'info'
        ];

        return redirect()->route('patients.index')
            ->with('notification', $notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete(); // Soft delete

        $notification = [
            'message' => 'Paciente eliminado exitosamente.',
            'alert-type' => 'warning'
        ];

        return redirect()->route('patients.index')
            ->with('notification', $notification);
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

        $notification = [
            'message' => 'Paciente reactivado exitosamente.',
            'alert-type' => 'success'
        ];

        return redirect()->route('patients.index')
            ->with('notification', $notification);
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
            'clinicalRecord',
            'gender',
            'civilStatus',
            'ethnicity',
            'linguisticCommunity',
            'country',
            'department',
            'municipality',
            'relatives'
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

    /**
     * Buscar familiares existentes por nombre o DPI (para AJAX)
     */
    public function searchRelatives(Request $request)
    {
        $term = $request->input('term', '');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        // Dividir el término de búsqueda por palabras para permitir búsqueda en cualquier orden
        $words = explode(' ', $term);
        $words = array_filter($words); // Eliminar espacios vacíos

        $query = \App\Models\PatientRelative::query();

        foreach ($words as $word) {
            $query->where(function ($q) use ($word) {
                $q->where('first_name', 'like', "%{$word}%")
                  ->orWhere('second_name', 'like', "%{$word}%")
                  ->orWhere('third_name', 'like', "%{$word}%")
                  ->orWhere('first_last_name', 'like', "%{$word}%")
                  ->orWhere('second_last_name', 'like', "%{$word}%")
                  ->orWhere('married_last_name', 'like', "%{$word}%")
                  ->orWhere('dpi', 'like', "%{$word}%");
            });
        }

        $results = $query
            ->select('id', 'first_name', 'second_name', 'first_last_name', 'second_last_name', 'married_last_name', 'dpi')
            ->limit(10)
            ->get()
            ->map(function ($r) {
                $name = trim("{$r->first_name} {$r->second_name} {$r->first_last_name} {$r->second_last_name}");
                return [
                    'id'                => $r->id,
                    'name'              => $name,
                    'dpi'               => $r->dpi,
                    'first_name'        => $r->first_name,
                    'second_name'       => $r->second_name,
                    'first_last_name'   => $r->first_last_name,
                    'second_last_name'  => $r->second_last_name,
                    'married_last_name' => $r->married_last_name,
                ];
            });

        return response()->json($results);
    }

    /**
     * Crear un familiar en tiempo real (sin salir del formulario)
     */
    public function storeRelativeAjax(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:100',
            'first_last_name' => 'required|string|max:100',
        ]);

        // Se guarda temporalmente sin patient_id (se asociará al guardar el paciente)
        // Usamos una instancia no persistida y la devolvemos al cliente:
        $data = [
            'first_name'       => $request->input('first_name'),
            'second_name'      => $request->input('second_name'),
            'first_last_name'  => $request->input('first_last_name'),
            'second_last_name' => $request->input('second_last_name'),
            'dpi'              => $request->input('dpi'),
        ];
        $name = trim("{$data['first_name']} {$data['second_name']} {$data['first_last_name']} {$data['second_last_name']}");

        // Devolvemos el registro para que el frontend pueda seleccionarlo automáticamente
        return response()->json([
            'id'        => null, // no persisted yet
            'name'      => $name,
            'dpi'       => $data['dpi'],
            'first_name'       => $data['first_name'],
            'second_name'      => $data['second_name'],
            'first_last_name'  => $data['first_last_name'],
            'second_last_name' => $data['second_last_name'],
        ]);
    }
}