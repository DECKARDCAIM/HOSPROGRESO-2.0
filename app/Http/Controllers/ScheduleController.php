<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Exports\ScheduleExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Schedule::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $status = $request->input('status', 'active');
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $perPage        = $request->input('per_page', 25);
        $allFilteredIds = (clone $query)->pluck('id')->toArray();
        $items          = $query->orderBy('id')->paginate($perPage)->appends($request->query());

        $total    = Schedule::count();
        $active   = Schedule::where('is_active', true)->count();
        $inactive = Schedule::where('is_active', false)->count();

        return view('modules.medical.schedules.index', compact('items', 'allFilteredIds', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('modules.medical.schedules.create');
    }

    public function store(StoreScheduleRequest $request)
    {
        $item = new Schedule();
        $item->name = $request->input('name');
        $item->save();

        $notification = ['message' => 'El turno "' . $item->name . '" se ha creado correctamente.', 'alert-type' => 'success'];
        return redirect()->route('schedules.index')->with(compact('notification'));
    }

    public function show(Schedule $schedule) {}

    public function edit(Schedule $schedule)
    {
        return view('modules.medical.schedules.edit', ['item' => $schedule]);
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->name = $request->input('name');
        $schedule->save();

        $notification = ['message' => 'El turno "' . $schedule->name . '" se ha actualizado correctamente.', 'alert-type' => 'info'];
        return redirect()->route('schedules.index')->with(compact('notification'));
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->is_active = false;
        $schedule->save();

        $notification = ['message' => 'El turno "' . $schedule->name . '" ha sido desactivado.', 'alert-type' => 'warning'];
        return redirect()->route('schedules.index')->with(compact('notification'));
    }

    public function restore(Schedule $schedule)
    {
        $schedule->is_active = true;
        $schedule->save();

        $notification = ['message' => 'El turno "' . $schedule->name . '" ha sido reactivado.', 'alert-type' => 'success'];
        return redirect()->route('schedules.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        Schedule::whereIn('id', $ids)->update(['is_active' => false]);
        return back()->with('success', count($ids) . ' turnos han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        Schedule::whereIn('id', $ids)->update(['is_active' => true]);
        return back()->with('success', count($ids) . ' turnos han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        return Excel::download(new ScheduleExport($ids), 'turnos.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        return Excel::download(new ScheduleExport($ids), 'turnos.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Schedule::whereIn('id', $ids)->orderBy('id')->get() : Schedule::orderBy('id')->get();
        $pdf = Pdf::loadView('modules.medical.schedules.print', compact('items'));
        return $pdf->download('turnos.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Schedule::whereIn('id', $ids)->orderBy('id')->get() : Schedule::orderBy('id')->get();
        return view('modules.medical.schedules.print', compact('items'));
    }
}
