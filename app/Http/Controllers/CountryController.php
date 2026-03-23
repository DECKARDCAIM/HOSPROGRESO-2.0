<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Exports\CountryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Country::query();

        // Buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por estado (activo por defecto)
        $status = $request->input('status', 'active');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $perPage            = $request->input('per_page', 25);
        $allFilteredIds     = (clone $query)->pluck('id')->toArray();
        $countries          = $query->orderBy('id')->paginate($perPage)->appends($request->query());
        
        $totalCountries     = Country::count();
        $activeCountries    = Country::where('is_active', true)->count();
        $inactiveCountries  = Country::where('is_active', false)->count();

        return view('modules.ubication.countries.index', compact(
            'countries', 'allFilteredIds', 'totalCountries', 'activeCountries', 'inactiveCountries'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.ubication.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request)
    {

        $countries = new Country();
        $countries->name = $request->input('name');
        $countries->save();
        $notification = [
            'message' => 'El pais ' . $countries->name . ' se ha creado correctamente.',
            'alert-type' => 'success'
        ];
        return redirect()->route('countries.index')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        return view('modules.ubication.countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {

        $country->name = $request->input('name');
        $country->save();
        $notification = [
            'message' => 'El pais ' . $country->name . ' se ha actualizado correctamente.',
            'alert-type' => 'info'
        ];
        return redirect()->route('countries.index')->with(compact('notification'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->is_active = false;
        $country->save();

        // Desactivación en cascada
        $departmentIds = $country->departments()->pluck('id');
        $country->departments()->update(['is_active' => false]);
        \App\Models\Municipality::whereIn('department_id', $departmentIds)->update(['is_active' => false]);

        $notification = [
            'message'    => 'El país ' . $country->name . ' ha sido desactivado correctamente.',
            'alert-type' => 'warning'
        ];
        return redirect()->route('countries.index')->with(compact('notification'));
    }

    /**
     * Reactivar el recurso.
     */
    public function restore(Country $country)
    {
        $country->is_active = true;
        $country->save();

        // Reactivación en cascada
        $departmentIds = $country->departments()->pluck('id');
        $country->departments()->update(['is_active' => true]);
        \App\Models\Municipality::whereIn('department_id', $departmentIds)->update(['is_active' => true]);

        $notification = [
            'message'    => 'El país ' . $country->name . ' ha sido reactivado correctamente.',
            'alert-type' => 'success'
        ];
        return redirect()->route('countries.index')->with(compact('notification'));
    }

    // ==========================================
    // ACCIONES MASIVAS
    // ==========================================
    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron países.');

        Country::whereIn('id', $ids)->update(['is_active' => false]);
        
        $departments = \App\Models\Department::whereIn('country_id', $ids)->get();
        if ($departments->count() > 0) {
            \App\Models\Department::whereIn('id', $departments->pluck('id'))->update(['is_active' => false]);
            \App\Models\Municipality::whereIn('department_id', $departments->pluck('id'))->update(['is_active' => false]);
        }

        return back()->with('success', count($ids) . ' países y sus dependencias han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron países.');

        Country::whereIn('id', $ids)->update(['is_active' => true]);
        
        $departments = \App\Models\Department::whereIn('country_id', $ids)->get();
        if ($departments->count() > 0) {
            \App\Models\Department::whereIn('id', $departments->pluck('id'))->update(['is_active' => true]);
            \App\Models\Municipality::whereIn('department_id', $departments->pluck('id'))->update(['is_active' => true]);
        }

        return back()->with('success', count($ids) . ' países y sus dependencias han sido reactivados.');
    }

    // ==========================================
    // EXPORTACIONES
    // ==========================================
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
        
        $pdf = Pdf::loadView('modules.ubication.countries.print', compact('countries'));
        return $pdf->download('paises.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $countries = count($ids) > 0 ? Country::whereIn('id', $ids)->orderBy('id')->get() : Country::orderBy('id')->get();
        
        return view('modules.ubication.countries.print', compact('countries'));
    }
}