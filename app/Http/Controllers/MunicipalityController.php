<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Department;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMunicipalityRequest;
use App\Http\Requests\UpdateMunicipalityRequest;
use App\Exports\MunicipalityExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class MunicipalityController extends Controller
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
        $query = Municipality::with('department.country');

        // Buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('department', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhereHas('country', function($q3) use ($search) {
                             $q3->where('name', 'LIKE', "%{$search}%");
                         });
                  });
            });
        }

        // Filtro por País (a través de depto)
        if ($request->filled('country_id')) {
            $query->whereHas('department', function($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        // Filtro por Departamento
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filtro por estado (activo por defecto)
        $status = $request->input('status', 'active');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $perPage                = $request->input('per_page', 25);
        $allFilteredIds         = (clone $query)->pluck('id')->toArray();
        $municipalities         = $query->orderBy('id')->paginate($perPage)->appends($request->query());
        
        $totalMunicipalities    = Municipality::count();
        $activeMunicipalities   = Municipality::where('is_active', true)->count();
        $inactiveMunicipalities = Municipality::where('is_active', false)->count();
        
        $countries              = Country::orderBy('id')->get();
        
        $departmentsQuery = Department::query();
        if ($request->filled('country_id')) {
            $departmentsQuery->where('country_id', $request->country_id);
        }
        $departments = $departmentsQuery->orderBy('id')->get();

        return view('modules.ubication.municipalities.index', compact(
            'municipalities', 'allFilteredIds', 'countries', 'departments',
            'totalMunicipalities', 'activeMunicipalities', 'inactiveMunicipalities'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $countries   = Country::all();
        $departments = collect();

        if ($request->filled('country_id')) {
            $departments = Department::where('country_id', $request->country_id)->get();
        }

        return view('modules.ubication.municipalities.create', compact('departments', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMunicipalityRequest $request)
    {

        $municipality                = new Municipality();
        $municipality->department_id = $request->department_id;
        $municipality->name          = $request->name;
        $municipality->save();

        $notification = [
            'message'    => 'El municipio ' . $municipality->name . ' se ha creado correctamente.',
            'alert-type' => 'success',
        ];

        return redirect()->route('municipalities.index')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipality $municipality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Municipality $municipality)
    {
        $countries   = Country::all();
        $departments = Department::all();

        return view('modules.ubication.municipalities.edit', compact('municipality', 'departments', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMunicipalityRequest $request, Municipality $municipality)
    {

        $municipality->name          = $request->input('name');
        $municipality->department_id = $request->input('department_id');
        $municipality->save();

        $notification = [
            'message'    => 'El municipio ' . $municipality->name . ' se ha actualizado correctamente.',
            'alert-type' => 'info',
        ];

        return redirect()->route('municipalities.index')->with(compact('notification'));
    }

    public function destroy(Municipality $municipality)
    {
        $municipality->is_active = false;
        $municipality->save();
        $notification = [
            'message'    => 'El municipio ' . $municipality->name . ' ha sido desactivado correctamente.',
            'alert-type' => 'warning'
        ];
        return redirect()->route('municipalities.index')->with(compact('notification'));
    }

    /**
     * Reactivar el recurso.
     */
    public function restore(Municipality $municipality)
    {
        $municipality->is_active = true;
        $municipality->save();
        $notification = [
            'message'    => 'El municipio ' . $municipality->name . ' ha sido reactivado correctamente.',
            'alert-type' => 'success'
        ];
        return redirect()->route('municipalities.index')->with(compact('notification'));
    }

    // ==========================================
    // ACCIONES MASIVAS
    // ==========================================
    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron municipios.');

        Municipality::whereIn('id', $ids)->update(['is_active' => false]);
        return back()->with('success', count($ids) . ' municipios han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron municipios.');

        Municipality::whereIn('id', $ids)->update(['is_active' => true]);
        return back()->with('success', count($ids) . ' municipios han sido reactivados.');
    }

    // ==========================================
    // EXPORTACIONES
    // ==========================================
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