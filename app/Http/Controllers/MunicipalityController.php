<?php

namespace App\Http\Controllers;

use App\Exports\MunicipalityExport;
use App\Http\Requests\StoreMunicipalityRequest;
use App\Http\Requests\UpdateMunicipalityRequest;
use App\Models\Country;
use App\Models\Department;
use App\Models\Municipality;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class MunicipalityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'municipalities_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['municipalities', 'departments', 'countries'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Municipality::with('department.country');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhereHas('department', function ($q2) use ($search) {
                            $q2->where('name', 'LIKE', "%{$search}%")
                                ->orWhereHas('country', function ($q3) use ($search) {
                                    $q3->where('name', 'LIKE', "%{$search}%");
                                });
                        });
                });
            }

            if ($request->filled('country_id')) {
                $query->whereHas('department', function ($q) use ($request) {
                    $q->where('country_id', $request->country_id);
                });
            }

            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            $status = $request->input('status', 'active');

            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }

            $perPage = $request->input('per_page', 25);

            $allFilteredIds = (clone $query)->pluck('id')->toArray();
            $municipalities = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $totalMunicipalities = Municipality::count();
            $activeMunicipalities = Municipality::where('is_active', true)->count();
            $inactiveMunicipalities = Municipality::where('is_active', false)->count();

            $countries = Country::where('is_active', true)->orderBy('name')->get();

            $departmentsQuery = Department::where('is_active', true);
            if ($request->filled('country_id')) {
                $departmentsQuery->where('country_id', $request->country_id);
            }
            $departments = $departmentsQuery->orderBy('name')->get();

            return compact('municipalities', 'allFilteredIds', 'countries', 'departments', 'totalMunicipalities', 'activeMunicipalities', 'inactiveMunicipalities');
        });

        return view('modules.ubication.municipalities.index', $data);
    }

    public function create(Request $request)
    {
        $countries = Cache::tags(['countries'])->remember('active_countries', now()->addDays(1), function () {
            return Country::where('is_active', true)->orderBy('name')->get();
        });

        $departments = collect();
        if ($request->filled('country_id')) {
            $departments = Department::where('country_id', $request->country_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return view('modules.ubication.municipalities.create', compact('departments', 'countries'));
    }

    public function store(StoreMunicipalityRequest $request)
    {
        $municipality = Municipality::create($request->validated());

        return redirect()->route('municipalities.index')->with('success', 'El municipio '.$municipality->name.' se ha creado correctamente.');
    }

    public function show(Municipality $municipality) {}

    public function edit(Municipality $municipality)
    {
        $countries = Cache::tags(['countries'])->remember('active_countries', now()->addDays(1), function () {
            return Country::where('is_active', true)->orderBy('name')->get();
        });

        $departments = Cache::tags(['departments'])->remember('active_departments', now()->addDays(1), function () {
            return Department::where('is_active', true)->orderBy('name')->get();
        });

        return view('modules.ubication.municipalities.edit', compact('municipality', 'departments', 'countries'));
    }

    public function update(UpdateMunicipalityRequest $request, Municipality $municipality)
    {
        $municipality->update($request->validated());

        return redirect()->route('municipalities.index')->with('success', 'El municipio '.$municipality->name.' se ha actualizado correctamente.');
    }

    public function destroy(Municipality $municipality)
    {
        $municipality->update(['is_active' => false]);

        Cache::tags(['municipalities'])->flush();

        return redirect()->route('municipalities.index')->with('success', 'El municipio '.$municipality->name.' ha sido desactivado correctamente.');
    }

    public function restore(Municipality $municipality)
    {
        $municipality->update(['is_active' => true]);

        Cache::tags(['municipalities'])->flush();

        return redirect()->route('municipalities.index')->with('success', 'El municipio '.$municipality->name.' ha sido reactivado correctamente.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron municipios.');
        }

        Municipality::whereIn('id', $ids)->update(['is_active' => false]);

        Cache::tags(['municipalities'])->flush();

        return back()->with('success', count($ids).' municipios han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron municipios.');
        }

        Municipality::whereIn('id', $ids)->update(['is_active' => true]);

        Cache::tags(['municipalities'])->flush();

        return back()->with('success', count($ids).' municipios han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new MunicipalityExport($ids), 'municipios.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new MunicipalityExport($ids), 'municipios.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $municipalities = count($ids) > 0 ? Municipality::whereIn('id', $ids)->orderBy('id')->get() : Municipality::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.ubication.municipalities.print', compact('municipalities'));

        return $pdf->download('municipios.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $municipalities = count($ids) > 0 ? Municipality::whereIn('id', $ids)->orderBy('id')->get() : Municipality::orderBy('id')->get();

        return view('modules.ubication.municipalities.print', compact('municipalities'));
    }
}
