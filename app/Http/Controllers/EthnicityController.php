<?php

namespace App\Http\Controllers;

use App\Exports\EthnicityExport;
use App\Http\Requests\StoreEthnicityRequest;
use App\Http\Requests\UpdateEthnicityRequest;
use App\Models\Ethnicity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class EthnicityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'ethnicities_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['ethnicities'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = Ethnicity::query();

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

            $total = Ethnicity::count();
            $active = Ethnicity::where('is_active', true)->count();
            $inactive = Ethnicity::where('is_active', false)->count();

            return compact('items', 'allFilteredIds', 'total', 'active', 'inactive');
        });

        return view('modules.maintenance.ethnicities.index', $data);
    }

    public function create()
    {
        return view('modules.maintenance.ethnicities.create');
    }

    public function store(StoreEthnicityRequest $request)
    {
        $item = Ethnicity::create($request->validated());

        return redirect()->route('maintenance.ethnicities.index')->with('success', 'La etnia "'.$item->name.'" se ha creado correctamente.');
    }

    public function show(Ethnicity $ethnicity) {}

    public function edit(Ethnicity $ethnicity)
    {
        return view('modules.maintenance.ethnicities.edit', ['item' => $ethnicity]);
    }

    public function update(UpdateEthnicityRequest $request, Ethnicity $ethnicity)
    {
        $ethnicity->update($request->validated());

        return redirect()->route('maintenance.ethnicities.index')->with('success', 'La etnia "'.$ethnicity->name.'" se ha actualizado correctamente.');
    }

    public function destroy(Ethnicity $ethnicity)
    {
        $ethnicity->update(['is_active' => false]);

        Cache::tags(['ethnicities'])->flush();

        return redirect()->route('maintenance.ethnicities.index')->with('success', 'La etnia "'.$ethnicity->name.'" ha sido desactivada.');
    }

    public function restore(Ethnicity $ethnicity)
    {
        $ethnicity->update(['is_active' => true]);

        Cache::tags(['ethnicities'])->flush();

        return redirect()->route('maintenance.ethnicities.index')->with('success', 'La etnia "'.$ethnicity->name.'" ha sido reactivada.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Ethnicity::whereIn('id', $ids)->update(['is_active' => false]);
        Cache::tags(['ethnicities'])->flush();

        return back()->with('success', count($ids).' etnias han sido desactivadas.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        Ethnicity::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['ethnicities'])->flush();

        return back()->with('success', count($ids).' etnias han sido reactivadas.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new EthnicityExport($ids), 'etnias.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new EthnicityExport($ids), 'etnias.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Ethnicity::whereIn('id', $ids)->orderBy('id')->get() : Ethnicity::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.maintenance.ethnicities.print', compact('items'));

        return $pdf->download('etnias.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Ethnicity::whereIn('id', $ids)->orderBy('id')->get() : Ethnicity::orderBy('id')->get();

        return view('modules.maintenance.ethnicities.print', compact('items'));
    }
}
