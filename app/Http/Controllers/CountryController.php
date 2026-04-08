<?php

namespace App\Http\Controllers;

use App\Exports\CountryExport;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use App\Models\Department;
use App\Models\Municipality;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'countries_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['countries'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Country::query();

            if ($request->filled('search')) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            }

            $status = $request->input('status', 'active');

            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }

            $perPage = $request->input('per_page', 25);

            $allFilteredIds = (clone $query)->pluck('id')->toArray();
            $countries = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $totalCountries = Country::count();
            $activeCountries = Country::where('is_active', true)->count();
            $inactiveCountries = Country::where('is_active', false)->count();

            return compact('countries', 'allFilteredIds', 'totalCountries', 'activeCountries', 'inactiveCountries');
        });

        return view('modules.maintenance.ubication.countries.index', $data);
    }

    public function create()
    {
        return view('modules.maintenance.ubication.countries.create');
    }

    public function store(StoreCountryRequest $request)
    {
        $country = Country::create($request->validated());

        return redirect()->route('maintenance.countries.index')->with('success', 'El país '.$country->name.' se ha creado correctamente.');
    }

    public function show(Country $country) {}

    public function edit(Country $country)
    {
        return view('modules.maintenance.ubication.countries.edit', compact('country'));
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->validated());

        return redirect()->route('maintenance.countries.index')->with('success', 'El país '.$country->name.' se ha actualizado correctamente.');
    }

    public function destroy(Country $country)
    {
        $country->update(['is_active' => false]);

        $departmentIds = $country->departments()->pluck('id');
        Department::whereIn('country_id', [$country->id])->update(['is_active' => false]);
        Municipality::whereIn('department_id', $departmentIds)->update(['is_active' => false]);

        Cache::tags(['countries'])->flush();

        return redirect()->route('maintenance.countries.index')->with('success', 'El país '.$country->name.' ha sido desactivado correctamente.');
    }

    public function restore(Country $country)
    {
        $country->update(['is_active' => true]);

        $departmentIds = $country->departments()->pluck('id');
        Department::whereIn('country_id', [$country->id])->update(['is_active' => true]);
        Municipality::whereIn('department_id', $departmentIds)->update(['is_active' => true]);

        Cache::tags(['countries'])->flush();

        return redirect()->route('maintenance.countries.index')->with('success', 'El país '.$country->name.' ha sido reactivado correctamente.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron países.');
        }

        Country::whereIn('id', $ids)->update(['is_active' => false]);

        $departments = Department::whereIn('country_id', $ids)->get();

        if ($departments->isNotEmpty()) {
            $departmentIds = $departments->pluck('id');
            Department::whereIn('id', $departmentIds)->update(['is_active' => false]);
            Municipality::whereIn('department_id', $departmentIds)->update(['is_active' => false]);
        }

        Cache::tags(['countries'])->flush();

        return back()->with('success', count($ids).' países y sus dependencias han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron países.');
        }

        Country::whereIn('id', $ids)->update(['is_active' => true]);

        $departments = Department::whereIn('country_id', $ids)->get();

        if ($departments->isNotEmpty()) {
            $departmentIds = $departments->pluck('id');
            Department::whereIn('id', $departmentIds)->update(['is_active' => true]);
            Municipality::whereIn('department_id', $departmentIds)->update(['is_active' => true]);
        }

        Cache::tags(['countries'])->flush();

        return back()->with('success', count($ids).' países y sus dependencias han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new CountryExport($ids), 'paises.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new CountryExport($ids), 'paises.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $countries = count($ids) > 0 ? Country::whereIn('id', $ids)->orderBy('id')->get() : Country::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.maintenance.ubication.countries.print', compact('countries'));

        return $pdf->download('paises.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $countries = count($ids) > 0 ? Country::whereIn('id', $ids)->orderBy('id')->get() : Country::orderBy('id')->get();

        return view('modules.maintenance.ubication.countries.print', compact('countries'));
    }
}
