<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Exports\DepartmentExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DepartmentController extends Controller
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
        $query = Department::with('country');

        // Buscador
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhereHas('country', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filtro por País
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Filtro por estado (activo por defecto)
        $status = $request->input('status', 'active');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $perPage             = $request->input('per_page', 25);
        $allFilteredIds      = (clone $query)->pluck('id')->toArray();
        $departments         = $query->orderBy('id')->paginate($perPage)->appends($request->query());
        
        $totalDepartments    = Department::count();
        $activeDepartments   = Department::where('is_active', true)->count();
        $inactiveDepartments = Department::where('is_active', false)->count();
        $countries           = Country::where('is_active', true)->orderBy('name')->get();

        return view('modules.ubication.departments.index', compact(
            'departments', 'allFilteredIds', 'countries', 'totalDepartments', 'activeDepartments', 'inactiveDepartments'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        return view('modules.ubication.departments.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {

        $departments = new Department();
        $departments->country_id = $request->input('country_id'); // Asigna el ID del país seleccionado
        $departments->name = $request->input('name');
        $departments->save();
        $notification = [
            'message' => 'El departamento ' . $departments->name . ' se ha creado correctamente.',
            'alert-type' => 'success'
        ];
        return redirect()->route('departments.index')->with(compact('notification'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        return view('modules.ubication.departments.edit', compact('department', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {

        $department->name = $request->input('name');
        $department->country_id = $request->input('country_id'); // Asigna el ID del país seleccionado
        $department->save();
        $notification = [
            'message' => 'El departamento ' . $department->name . ' se ha actualizado correctamente.',
            'alert-type' => 'info'
        ];
        return redirect()->route('departments.index')->with(compact('notification'));
    }

    public function destroy(Department $department)
    {
        $department->is_active = false;
        $department->save();

        // Desactivación en cascada
        $department->municipalities()->update(['is_active' => false]);

        $notification = [
            'message'    => 'El departamento ' . $department->name . ' ha sido desactivado correctamente.',
            'alert-type' => 'warning'
        ];
        return redirect()->route('departments.index')->with(compact('notification'));
    }

    /**
     * Reactivar el recurso.
     */
    public function restore(Department $department)
    {
        $department->is_active = true;
        $department->save();

        // Reactivación en cascada
        $department->municipalities()->update(['is_active' => true]);

        $notification = [
            'message'    => 'El departamento ' . $department->name . ' ha sido reactivado correctamente.',
            'alert-type' => 'success'
        ];
        return redirect()->route('departments.index')->with(compact('notification'));
    }

    // ==========================================
    // ACCIONES MASIVAS
    // ==========================================
    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron departamentos.');

        Department::whereIn('id', $ids)->update(['is_active' => false]);
        
        \App\Models\Municipality::whereIn('department_id', $ids)->update(['is_active' => false]);

        return back()->with('success', count($ids) . ' departamentos y sus dependencias han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron departamentos.');

        Department::whereIn('id', $ids)->update(['is_active' => true]);
        
        \App\Models\Municipality::whereIn('department_id', $ids)->update(['is_active' => true]);

        return back()->with('success', count($ids) . ' departamentos y sus dependencias han sido reactivados.');
    }

    // ==========================================
    // EXPORTACIONES
    // ==========================================
    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        return Excel::download(new DepartmentExport($ids), 'departamentos.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        return Excel::download(new DepartmentExport($ids), 'departamentos.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $departments = count($ids) > 0 ? Department::whereIn('id', $ids)->orderBy('id')->get() : Department::orderBy('id')->get();
        
        $pdf = Pdf::loadView('modules.ubication.departments.print', compact('departments'));
        return $pdf->download('departamentos.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $departments = count($ids) > 0 ? Department::whereIn('id', $ids)->orderBy('id')->get() : Department::orderBy('id')->get();
        
        return view('modules.ubication.departments.print', compact('departments'));
    }
}