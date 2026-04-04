<?php

namespace App\Http\Controllers;

use App\Exports\DepartmentExport;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Country;
use App\Models\Department;
use App\Models\Municipality;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'departments_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['departments', 'countries'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Department::with('country');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhereHas('country', function ($q2) use ($search) {
                            $q2->where('name', 'LIKE', "%{$search}%");
                        });
                });
            }

            if ($request->filled('country_id')) {
                $query->where('country_id', $request->country_id);
            }

            $status = $request->input('status', 'active');

            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }

            $perPage = $request->input('per_page', 25);

            $allFilteredIds = (clone $query)->pluck('id')->toArray();
            $departments = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $totalDepartments = Department::count();
            $activeDepartments = Department::where('is_active', true)->count();
            $inactiveDepartments = Department::where('is_active', false)->count();
            $countries = Country::where('is_active', true)->orderBy('name')->get();

            return compact('departments', 'allFilteredIds', 'countries', 'totalDepartments', 'activeDepartments', 'inactiveDepartments');
        });

        return view('modules.ubication.departments.index', $data);
    }

    public function create()
    {
        $countries = Cache::tags(['countries'])->remember('active_countries', now()->addDays(1), function () {
            return Country::where('is_active', true)->orderBy('name')->get();
        });

        return view('modules.ubication.departments.create', compact('countries'));
    }

    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->validated());

        return redirect()->route('departments.index')->with('success', 'El departamento '.$department->name.' se ha creado correctamente.');
    }

    public function show(Department $department) {}

    public function edit(Department $department)
    {
        $countries = Cache::tags(['countries'])->remember('active_countries', now()->addDays(1), function () {
            return Country::where('is_active', true)->orderBy('name')->get();
        });

        return view('modules.ubication.departments.edit', compact('department', 'countries'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        return redirect()->route('departments.index')->with('success', 'El departamento '.$department->name.' se ha actualizado correctamente.');
    }

    public function destroy(Department $department)
    {
        $department->update(['is_active' => false]);
        $department->municipalities()->update(['is_active' => false]);

        Cache::tags(['departments'])->flush();

        return redirect()->route('departments.index')->with('success', 'El departamento '.$department->name.' ha sido desactivado correctamente.');
    }

    public function restore(Department $department)
    {
        $department->update(['is_active' => true]);
        $department->municipalities()->update(['is_active' => true]);

        Cache::tags(['departments'])->flush();

        return redirect()->route('departments.index')->with('success', 'El departamento '.$department->name.' ha sido reactivado correctamente.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron departamentos.');
        }

        Department::whereIn('id', $ids)->update(['is_active' => false]);
        Municipality::whereIn('department_id', $ids)->update(['is_active' => false]);

        Cache::tags(['departments'])->flush();

        return back()->with('success', count($ids).' departamentos y sus dependencias han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron departamentos.');
        }

        Department::whereIn('id', $ids)->update(['is_active' => true]);
        Municipality::whereIn('department_id', $ids)->update(['is_active' => true]);

        Cache::tags(['departments'])->flush();

        return back()->with('success', count($ids).' departamentos y sus dependencias han sido reactivados.');
    }

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
