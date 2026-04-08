<?php

namespace App\Http\Controllers;

use App\Exports\DisabilityExport;
use App\Http\Requests\StoreDisabilityRequest;
use App\Http\Requests\UpdateDisabilityRequest;
use App\Models\Disability;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class DisabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'disabilities_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['disabilities'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Disability::query();

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

            $total = Disability::count();
            $active = Disability::where('is_active', true)->count();
            $inactive = Disability::where('is_active', false)->count();

            return compact('items', 'allFilteredIds', 'total', 'active', 'inactive');
        });

        return view('modules.maintenance.disabilities.index', $data);
    }

    public function create()
    {
        return view('modules.maintenance.disabilities.create');
    }

    public function store(StoreDisabilityRequest $request)
    {
        $item = Disability::create($request->validated());

        return redirect()->route('maintenance.disabilities.index')->with('success', 'La discapacidad "'.$item->name.'" se ha creado correctamente.');
    }

    public function show(Disability $disability) {}

    public function edit(Disability $disability)
    {
        return view('modules.maintenance.disabilities.edit', ['item' => $disability]);
    }

    public function update(UpdateDisabilityRequest $request, Disability $disability)
    {
        $disability->update($request->validated());

        return redirect()->route('maintenance.disabilities.index')->with('success', 'La discapacidad "'.$disability->name.'" se ha actualizado correctamente.');
    }

    public function destroy(Disability $disability)
    {
        $disability->update(['is_active' => false]);

        Cache::tags(['disabilities'])->flush();

        return redirect()->route('maintenance.disabilities.index')->with('success', 'La discapacidad "'.$disability->name.'" ha sido desactivada.');
    }

    public function restore(Disability $disability)
    {
        $disability->update(['is_active' => true]);

        Cache::tags(['disabilities'])->flush();

        return redirect()->route('maintenance.disabilities.index')->with('success', 'La discapacidad "'.$disability->name.'" ha sido reactivada.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Disability::whereIn('id', $ids)->update(['is_active' => false]);
        Cache::tags(['disabilities'])->flush();

        return back()->with('success', count($ids).' discapacidades han sido desactivadas.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Disability::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['disabilities'])->flush();

        return back()->with('success', count($ids).' discapacidades han sido reactivadas.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new DisabilityExport($ids), 'discapacidades.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new DisabilityExport($ids), 'discapacidades.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Disability::whereIn('id', $ids)->orderBy('id')->get() : Disability::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.maintenance.disabilities.print', compact('items'));

        return $pdf->download('discapacidades.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Disability::whereIn('id', $ids)->orderBy('id')->get() : Disability::orderBy('id')->get();

        return view('modules.maintenance.disabilities.print', compact('items'));
    }
}
