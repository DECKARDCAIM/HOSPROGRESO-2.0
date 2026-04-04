<?php

namespace App\Http\Controllers;

use App\Exports\WorkDepartmentExport;
use App\Http\Requests\StoreWorkDepartmentRequest;
use App\Http\Requests\UpdateWorkDepartmentRequest;
use App\Models\WorkDepartment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class WorkDepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'work_departments_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['work_departments'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = WorkDepartment::query();

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
            $workDepartments = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $totalCount = WorkDepartment::count();
            $activeCount = WorkDepartment::where('is_active', true)->count();
            $inactiveCount = WorkDepartment::where('is_active', false)->count();

            return compact('workDepartments', 'allFilteredIds', 'totalCount', 'activeCount', 'inactiveCount');
        });

        return view('modules.maintenance.work-departments.index', $data);
    }

    public function create()
    {
        return view('modules.maintenance.work-departments.create');
    }

    public function store(StoreWorkDepartmentRequest $request)
    {
        $item = WorkDepartment::create($request->validated());

        $notification = [
            'message' => 'El departamento '.$item->name.' se ha creado correctamente.',
            'alert-type' => 'success',
        ];

        return redirect()->route('work-departments.index')->with(compact('notification'));
    }

    public function show(WorkDepartment $workDepartment) {}

    public function edit(WorkDepartment $workDepartment)
    {
        return view('modules.maintenance.work-departments.edit', compact('workDepartment'));
    }

    public function update(UpdateWorkDepartmentRequest $request, WorkDepartment $workDepartment)
    {
        $workDepartment->update($request->validated());

        $notification = [
            'message' => 'El departamento '.$workDepartment->name.' se ha actualizado correctamente.',
            'alert-type' => 'info',
        ];

        return redirect()->route('work-departments.index')->with(compact('notification'));
    }

    public function destroy(WorkDepartment $workDepartment)
    {
        $workDepartment->update(['is_active' => false]);

        $notification = [
            'message' => 'El departamento '.$workDepartment->name.' ha sido desactivado correctamente.',
            'alert-type' => 'warning',
        ];

        return redirect()->route('work-departments.index')->with(compact('notification'));
    }

    public function restore(WorkDepartment $workDepartment)
    {
        $workDepartment->update(['is_active' => true]);

        $notification = [
            'message' => 'El departamento '.$workDepartment->name.' ha sido reactivado correctamente.',
            'alert-type' => 'success',
        ];

        return redirect()->route('work-departments.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron departamentos.');
        }

        WorkDepartment::whereIn('id', $ids)->update(['is_active' => false]);

        // Aquí sí es necesario el flush manual porque el whereIn()->update() no dispara eventos
        Cache::tags(['work_departments'])->flush();

        return back()->with('success', count($ids).' departamentos han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron departamentos.');
        }

        WorkDepartment::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['work_departments'])->flush();

        return back()->with('success', count($ids).' departamentos han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new WorkDepartmentExport($ids), 'departamentos_trabajo.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new WorkDepartmentExport($ids), 'departamentos_trabajo.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? WorkDepartment::whereIn('id', $ids)->orderBy('id')->get() : WorkDepartment::orderBy('id')->get();
        $pdf = Pdf::loadView('modules.maintenance.work-departments.print', compact('items'));

        return $pdf->download('departamentos_trabajo.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? WorkDepartment::whereIn('id', $ids)->orderBy('id')->get() : WorkDepartment::orderBy('id')->get();

        return view('modules.maintenance.work-departments.print', compact('items'));
    }
}
