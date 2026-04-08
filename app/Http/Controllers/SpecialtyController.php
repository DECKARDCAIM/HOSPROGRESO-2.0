<?php

namespace App\Http\Controllers;

use App\Exports\SpecialtyExport;
use App\Http\Requests\StoreSpecialtyRequest;
use App\Http\Requests\UpdateSpecialtyRequest;
use App\Models\Specialty;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class SpecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'specialties_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['specialties'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Specialty::query();

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

            $total = Specialty::count();
            $active = Specialty::where('is_active', true)->count();
            $inactive = Specialty::where('is_active', false)->count();

            return compact('items', 'allFilteredIds', 'total', 'active', 'inactive');
        });

        return view('modules.maintenance.specialties.index', $data);
    }

    public function create()
    {
        return view('modules.maintenance.specialties.create');
    }

    public function store(StoreSpecialtyRequest $request)
    {
        $item = Specialty::create($request->validated());

        return redirect()->route('maintenance.specialties.index')->with('success', 'La especialidad "'.$item->name.'" se ha creado correctamente.');
    }

    public function show(Specialty $specialty) {}

    public function edit(Specialty $specialty)
    {
        return view('modules.maintenance.specialties.edit', ['item' => $specialty]);
    }

    public function update(UpdateSpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->validated());

        return redirect()->route('maintenance.specialties.index')->with('success', 'La especialidad "'.$specialty->name.'" se ha actualizado correctamente.');
    }

    public function destroy(Specialty $specialty)
    {
        $specialty->update(['is_active' => false]);

        Cache::tags(['specialties'])->flush();

        return redirect()->route('maintenance.specialties.index')->with('success', 'La especialidad "'.$specialty->name.'" ha sido desactivada.');
    }

    public function restore(Specialty $specialty)
    {
        $specialty->update(['is_active' => true]);

        Cache::tags(['specialties'])->flush();

        return redirect()->route('maintenance.specialties.index')->with('success', 'La especialidad "'.$specialty->name.'" ha sido reactivada.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Specialty::whereIn('id', $ids)->update(['is_active' => false]);
        Cache::tags(['specialties'])->flush();

        return back()->with('success', count($ids).' especialidades han sido desactivadas.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Specialty::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['specialties'])->flush();

        return back()->with('success', count($ids).' especialidades han sido reactivadas.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new SpecialtyExport($ids), 'especialidades.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new SpecialtyExport($ids), 'especialidades.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Specialty::whereIn('id', $ids)->orderBy('id')->get() : Specialty::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.maintenance.specialties.print', compact('items'));

        return $pdf->download('especialidades.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Specialty::whereIn('id', $ids)->orderBy('id')->get() : Specialty::orderBy('id')->get();

        return view('modules.maintenance.specialties.print', compact('items'));
    }
}
