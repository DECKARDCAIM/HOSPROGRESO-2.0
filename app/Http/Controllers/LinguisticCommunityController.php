<?php

namespace App\Http\Controllers;

use App\Exports\LinguisticCommunityExport;
use App\Http\Requests\StoreLinguisticCommunityRequest;
use App\Http\Requests\UpdateLinguisticCommunityRequest;
use App\Models\LinguisticCommunity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class LinguisticCommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'linguistic_communities_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['linguistic_communities'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = LinguisticCommunity::query();

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

            $total = LinguisticCommunity::count();
            $active = LinguisticCommunity::where('is_active', true)->count();
            $inactive = LinguisticCommunity::where('is_active', false)->count();

            return compact('items', 'allFilteredIds', 'total', 'active', 'inactive');
        });

        return view('modules.maintenance.linguistic-communities.index', $data);
    }

    public function create()
    {
        return view('modules.maintenance.linguistic-communities.create');
    }

    public function store(StoreLinguisticCommunityRequest $request)
    {
        $item = LinguisticCommunity::create($request->validated());

        return redirect()->route('maintenance.linguistic-communities.index')->with('success', 'El idioma "'.$item->name.'" se ha creado correctamente.');
    }

    public function show(LinguisticCommunity $linguisticCommunity) {}

    public function edit(LinguisticCommunity $linguisticCommunity)
    {
        return view('modules.maintenance.linguistic-communities.edit', ['item' => $linguisticCommunity]);
    }

    public function update(UpdateLinguisticCommunityRequest $request, LinguisticCommunity $linguisticCommunity)
    {
        $linguisticCommunity->update($request->validated());

        return redirect()->route('maintenance.linguistic-communities.index')->with('success', 'El idioma "'.$linguisticCommunity->name.'" se ha actualizado correctamente.');
    }

    public function destroy(LinguisticCommunity $linguisticCommunity)
    {
        $linguisticCommunity->update(['is_active' => false]);

        Cache::tags(['linguistic_communities'])->flush();

        return redirect()->route('maintenance.linguistic-communities.index')->with('success', 'El idioma "'.$linguisticCommunity->name.'" ha sido desactivado.');
    }

    public function restore(LinguisticCommunity $linguisticCommunity)
    {
        $linguisticCommunity->update(['is_active' => true]);

        Cache::tags(['linguistic_communities'])->flush();

        return redirect()->route('maintenance.linguistic-communities.index')->with('success', 'El idioma "'.$linguisticCommunity->name.'" ha sido reactivado.');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        LinguisticCommunity::whereIn('id', $ids)->update(['is_active' => false]);
        Cache::tags(['linguistic_communities'])->flush();

        return back()->with('success', count($ids).' idiomas han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        LinguisticCommunity::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['linguistic_communities'])->flush();

        return back()->with('success', count($ids).' idiomas han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new LinguisticCommunityExport($ids), 'idiomas.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new LinguisticCommunityExport($ids), 'idiomas.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? LinguisticCommunity::whereIn('id', $ids)->orderBy('id')->get() : LinguisticCommunity::orderBy('id')->get();

        $pdf = Pdf::loadView('modules.maintenance.linguistic-communities.print', compact('items'));

        return $pdf->download('idiomas.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? LinguisticCommunity::whereIn('id', $ids)->orderBy('id')->get() : LinguisticCommunity::orderBy('id')->get();

        return view('modules.maintenance.linguistic-communities.print', compact('items'));
    }
}
