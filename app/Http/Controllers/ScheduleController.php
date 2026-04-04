<?php

namespace App\Http\Controllers;

use App\Exports\ScheduleExport;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'schedules_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['schedules'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
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

            $perPage = $request->input('per_page', 25);

            $allFilteredIds = (clone $query)->pluck('id')->toArray();
            $items = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $total = Schedule::count();
            $active = Schedule::where('is_active', true)->count();
            $inactive = Schedule::where('is_active', false)->count();

            return compact('items', 'allFilteredIds', 'total', 'active', 'inactive');
        });

        return view('modules.medical.schedules.index', $data);
    }

    public function create()
    {
        return view('modules.medical.schedules.create');
    }

    public function store(StoreScheduleRequest $request)
    {
        $item = Schedule::create($request->validated());

        return redirect()->route('schedules.index')->with('success', 'El turno "'.$item->name.'" se ha creado correctamente.');
    }

    public function show(Schedule $schedule) {}

    public function edit(Schedule $schedule)
    {
        return view('modules.medical.schedules.edit', ['item' => $schedule]);
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());

        return redirect()->route('schedules.index')->with('success', 'El turno "'.$schedule->name.'" se ha actualizado correctamente.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->update(['is_active' => false]);

        Cache::tags(['schedules'])->flush();

        return redirect()->route('schedules.index')->with('success', 'El turno "'.$schedule->name.'" ha sido desactivado.');
    }

    public function restore(Schedule $schedule)
    {
        $schedule->update(['is_active' => true]);

        Cache::tags(['schedules'])->flush();

        return redirect()->route('schedules.index')->with('success', 'El turno "'.$schedule->name.'" ha sido reactivado.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Schedule::whereIn('id', $ids)->update(['is_active' => false]);
        Cache::tags(['schedules'])->flush();

        return back()->with('success', count($ids).' turnos han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Schedule::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['schedules'])->flush();

        return back()->with('success', count($ids).' turnos han sido reactivados.');
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
