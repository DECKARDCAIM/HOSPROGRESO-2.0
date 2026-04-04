<?php

namespace App\Http\Controllers;

use App\Exports\PatientsExport;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Allergy;
use App\Models\CivilStatus;
use App\Models\ClinicalRecord;
use App\Models\Country;
use App\Models\Department;
use App\Models\Disability;
use App\Models\Ethnicity;
use App\Models\Gender;
use App\Models\LinguisticCommunity;
use App\Models\Municipality;
use App\Models\Patient;
use App\Models\PatientRelative;
use App\Models\RelationshipType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'patients_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['patients', 'countries', 'departments', 'municipalities', 'genders', 'civil_statuses', 'ethnicities', 'linguistic_communities'])
            ->remember($cacheKey, now()->addHours(2), function () use ($request) {
                $query = Patient::with([
                    'clinicalRecord',
                    'gender',
                    'civilStatus',
                    'ethnicity',
                    'linguisticCommunity',
                    'municipality.department.country',
                    'relatives',
                ]);

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
                if ($request->filled('birth_date_from')) {
                    $query->whereDate('birth_date', '>=', $request->birth_date_from);
                }
                if ($request->filled('birth_date_to')) {
                    $query->whereDate('birth_date', '<=', $request->birth_date_to);
                }

                if ($request->input('status') === 'inactive') {
                    $query->onlyTrashed();
                } else {
                    $query->withoutTrashed();
                }

                $perPage = $request->get('per_page', 25);
                $patients = $query->latest()->paginate($perPage)->appends($request->query());

                $totalPatients = Patient::count();
                $genderStats = Patient::selectRaw('gender_id, COUNT(*) as total')
                    ->groupBy('gender_id')
                    ->pluck('total', 'gender_id');

                $maleCount = $genderStats[1] ?? 0;
                $femaleCount = $genderStats[2] ?? 0;
                $malePercentage = $totalPatients > 0 ? round(($maleCount / $totalPatients) * 100, 1) : 0;
                $femalePercentage = $totalPatients > 0 ? round(($femaleCount / $totalPatients) * 100, 1) : 0;

                $countries = Country::where('is_active', true)->orderBy('name')->get();
                $departments = $request->filled('country_id')
                    ? Department::where('country_id', $request->country_id)->where('is_active', true)->orderBy('name')->get()
                    : collect();
                $municipalities = $request->filled('department_id')
                    ? Municipality::where('department_id', $request->department_id)->where('is_active', true)->orderBy('name')->get()
                    : collect();

                $genders = Gender::where('is_active', true)->orderBy('name')->get();
                $civilStatuses = CivilStatus::where('is_active', true)->orderBy('name')->get();
                $ethnicities = Ethnicity::where('is_active', true)->orderBy('name')->get();
                $linguisticCommunities = LinguisticCommunity::where('is_active', true)->orderBy('name')->get();

                return compact(
                    'patients', 'countries', 'departments', 'municipalities', 'genders',
                    'civilStatuses', 'ethnicities', 'linguisticCommunities', 'totalPatients',
                    'malePercentage', 'femalePercentage'
                );
            });

        return view('modules.patient.index', $data);
    }

    public function create()
    {
        $data = $this->getCachedCatalogs();

        return view('modules.patient.create', $data);
    }

    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            $patient = Patient::create($validated);

            $year = date('Y');
            $month = date('m');

            $lastRecord = ClinicalRecord::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('id', 'desc')
                ->first();

            $correlative = 1;
            if ($lastRecord) {
                $parts = explode('-', $lastRecord->record_number);
                $correlative = (count($parts) == 4) ? intval($parts[3]) + 1 : ClinicalRecord::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
            }

            $paddedCorrelative = str_pad($correlative, 4, '0', STR_PAD_LEFT);
            $newRecordNumber = "EXP-{$year}-{$month}-{$paddedCorrelative}";

            $patient->clinicalRecord()->create(['record_number' => $newRecordNumber]);

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
                        'cui' => $relativeData['cui'] ?? null,
                    ]);
                }
            }

            $patient->allergies()->sync($request->input('allergies', []));
            $patient->disabilities()->sync($request->input('disabilities', []));
        });

        return redirect()->route('patients.index')->with('success', 'Paciente registrado exitosamente.');
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'gender', 'civilStatus', 'ethnicity', 'linguisticCommunity', 'municipality.department.country',
        ]);

        return view('modules.patient.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $patient->load('relatives');
        $data = $this->getCachedCatalogs();

        $data['departments'] = ($patient->municipality && $patient->municipality->department)
            ? Department::where('country_id', $patient->municipality->department->country_id)->where('is_active', true)->orderBy('name')->get()
            : collect();

        $data['municipalities'] = $patient->municipality
            ? Municipality::where('department_id', $patient->municipality->department_id)->where('is_active', true)->orderBy('name')->get()
            : collect();

        $data['patient'] = $patient;
        $data['selectedAllergyIds'] = $patient->allergies()->pluck('allergies.id')->toArray();
        $data['selectedDisabilityIds'] = $patient->disabilities()->pluck('disabilities.id')->toArray();

        $data['existingRelatives'] = $patient->relatives->map(function ($r) {
            return [
                'id' => $r->id,
                'first_name' => $r->first_name,
                'second_name' => $r->second_name,
                'third_name' => $r->third_name,
                'first_last_name' => $r->first_last_name,
                'second_last_name' => $r->second_last_name,
                'married_last_name' => $r->married_last_name,
                'cui' => $r->cui,
                'relationship_type_id' => $r->relationship_type_id,
                'name' => trim(implode(' ', array_filter([
                    $r->first_name, $r->second_name, $r->third_name,
                    $r->first_last_name, $r->second_last_name,
                ]))),
            ];
        })->values()->toArray();

        return view('modules.patient.edit', $data);
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $patient) {
            $patient->update($validated);

            $patient->relatives()->delete();
            if ($request->has('relatives') && is_array($request->input('relatives'))) {
                foreach ($request->input('relatives') as $relativeData) {
                    if (empty($relativeData['first_name']) || empty($relativeData['first_last_name'])) {
                        continue;
                    }
                    $patient->relatives()->create([
                        'relationship_type_id' => $relativeData['relationship_type_id'],
                        'first_name' => $relativeData['first_name'],
                        'second_name' => $relativeData['second_name'] ?? null,
                        'third_name' => $relativeData['third_name'] ?? null,
                        'first_last_name' => $relativeData['first_last_name'],
                        'second_last_name' => $relativeData['second_last_name'] ?? null,
                        'married_last_name' => $relativeData['married_last_name'] ?? null,
                        'cui' => $relativeData['cui'] ?? null,
                    ]);
                }
            }

            $patient->allergies()->sync($request->input('allergies', []));
            $patient->disabilities()->sync($request->input('disabilities', []));
        });

        return redirect()->route('patients.index')->with('success', 'Paciente actualizado exitosamente.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente eliminado exitosamente.');
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:patients,id',
        ]);

        Patient::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pacientes eliminados exitosamente.',
        ]);
    }

    public function restore(Request $request, $id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('patients.index')->with('success', 'Paciente reactivado exitosamente.');
    }

    public function exportExcel(Request $request)
    {
        $query = $this->buildExportQuery($request);

        return Excel::download(new PatientsExport($query->get()), 'pacientes_'.date('Y-m-d_His').'.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $patients = $this->buildExportQuery($request)->get();
        $filename = 'pacientes_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($patients) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID', 'Nombre Completo', 'CUI', 'Fecha de Nacimiento', 'Edad', 'Género',
                'Estado Civil', 'Etnia', 'Comunidad Lingüística', 'Escolaridad', 'Ocupación',
                'Departamento', 'Municipio', 'Lugar', 'Fecha de Registro',
            ], ';');

            foreach ($patients as $patient) {
                fputcsv($file, [
                    $patient->id,
                    $patient->full_name,
                    $patient->cui ?: '',
                    $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '',
                    $patient->age ?: '',
                    $patient->gender?->name ?: '',
                    $patient->civilStatus?->name ?: '',
                    $patient->ethnicity?->name ?: '',
                    $patient->linguisticCommunity?->name ?: '',
                    $patient->education ?: '',
                    $patient->occupation ?: '',
                    $patient->department?->name ?: '',
                    $patient->municipality?->name ?: '',
                    $patient->place ?: '',
                    $patient->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF(Request $request)
    {
        $patients = $this->buildExportQuery($request)->get();
        $pdf = Pdf::loadView('modules.patient.export-pdf', compact('patients'));

        return $pdf->download('pacientes_'.date('Y-m-d_His').'.pdf');
    }

    private function buildExportQuery(Request $request)
    {
        $query = Patient::with([
            'clinicalRecord', 'gender', 'civilStatus', 'ethnicity',
            'linguisticCommunity', 'country', 'department', 'municipality', 'relatives',
        ]);

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

        if ($request->has('ids') && is_array($request->ids) && count($request->ids) > 0) {
            $query->whereIn('id', $request->ids);
        }

        return $query->latest();
    }

    public function getDepartmentsByCountry(Request $request)
    {
        $departments = Department::where('country_id', $request->country_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($departments);
    }

    public function getMunicipalitiesByDepartment(Request $request)
    {
        $municipalities = Municipality::where('department_id', $request->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($municipalities);
    }

    public function searchRelatives(Request $request)
    {
        $term = $request->input('term', '');
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $words = array_filter(explode(' ', $term));
        $query = PatientRelative::query();

        foreach ($words as $word) {
            $query->where(function ($q) use ($word) {
                $q->where('first_name', 'like', "%{$word}%")
                    ->orWhere('second_name', 'like', "%{$word}%")
                    ->orWhere('third_name', 'like', "%{$word}%")
                    ->orWhere('first_last_name', 'like', "%{$word}%")
                    ->orWhere('second_last_name', 'like', "%{$word}%")
                    ->orWhere('married_last_name', 'like', "%{$word}%")
                    ->orWhere('cui', 'like', "%{$word}%");
            });
        }

        $results = $query->select('id', 'first_name', 'second_name', 'first_last_name', 'second_last_name', 'married_last_name', 'cui')
            ->limit(10)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'name' => trim("{$r->first_name} {$r->second_name} {$r->first_last_name} {$r->second_last_name}"),
                    'cui' => $r->cui,
                    'first_name' => $r->first_name,
                    'second_name' => $r->second_name,
                    'first_last_name' => $r->first_last_name,
                    'second_last_name' => $r->second_last_name,
                    'married_last_name' => $r->married_last_name,
                ];
            });

        return response()->json($results);
    }

    public function storeRelativeAjax(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'first_last_name' => 'required|string|max:100',
        ]);

        $data = $request->only(['first_name', 'second_name', 'first_last_name', 'second_last_name', 'cui']);
        $name = trim("{$data['first_name']} {$data['second_name']} {$data['first_last_name']} {$data['second_last_name']}");

        return response()->json(array_merge(['id' => null, 'name' => $name], $data));
    }

    private function getCachedCatalogs()
    {
        return [
            'countries' => Cache::tags(['countries'])->remember('active_countries', now()->addDays(1), fn () => Country::where('is_active', true)->orderBy('name')->get()),
            'genders' => Cache::tags(['genders'])->remember('active_genders', now()->addDays(1), fn () => Gender::where('is_active', true)->orderBy('name')->get()),
            'civilStatuses' => Cache::tags(['civil_statuses'])->remember('active_civil_statuses', now()->addDays(1), fn () => CivilStatus::where('is_active', true)->orderBy('name')->get()),
            'ethnicities' => Cache::tags(['ethnicities'])->remember('active_ethnicities', now()->addDays(1), fn () => Ethnicity::where('is_active', true)->orderBy('name')->get()),
            'linguisticCommunities' => Cache::tags(['linguistic_communities'])->remember('active_linguistic_communities', now()->addDays(1), fn () => LinguisticCommunity::where('is_active', true)->orderBy('name')->get()),
            'relationshipTypes' => Cache::tags(['relationship_types'])->remember('active_relationship_types', now()->addDays(1), fn () => RelationshipType::where('is_active', true)->orderBy('name')->get()),
            'allergies' => Cache::tags(['allergies'])->remember('active_allergies', now()->addDays(1), fn () => Allergy::where('is_active', true)->orderBy('name')->get()),
            'disabilities' => Cache::tags(['disabilities'])->remember('active_disabilities', now()->addDays(1), fn () => Disability::where('is_active', true)->orderBy('name')->get()),
        ];
    }
}
