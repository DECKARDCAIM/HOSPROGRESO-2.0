<?php

namespace App\Http\Controllers;

use App\Models\LinguisticCommunity;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLinguisticCommunityRequest;
use App\Http\Requests\UpdateLinguisticCommunityRequest;
use App\Exports\LinguisticCommunityExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LinguisticCommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
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

        $perPage        = $request->input('per_page', 25);
        $allFilteredIds = (clone $query)->pluck('id')->toArray();
        $items          = $query->orderBy('id')->paginate($perPage)->appends($request->query());

        $total    = LinguisticCommunity::count();
        $active   = LinguisticCommunity::where('is_active', true)->count();
        $inactive = LinguisticCommunity::where('is_active', false)->count();

        return view('modules.patient.linguistic-communities.index', compact('items', 'allFilteredIds', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('modules.patient.linguistic-communities.create');
    }

    public function store(StoreLinguisticCommunityRequest $request)
    {
        $item = new LinguisticCommunity();
        $item->name = $request->input('name');
        $item->save();

        $notification = ['message' => 'El idioma "' . $item->name . '" se ha creado correctamente.', 'alert-type' => 'success'];
        return redirect()->route('linguistic-communities.index')->with(compact('notification'));
    }

    public function show(LinguisticCommunity $linguisticCommunity) {}

    public function edit(LinguisticCommunity $linguisticCommunity)
    {
        return view('modules.patient.linguistic-communities.edit', ['item' => $linguisticCommunity]);
    }

    public function update(UpdateLinguisticCommunityRequest $request, LinguisticCommunity $linguisticCommunity)
    {
        $linguisticCommunity->name = $request->input('name');
        $linguisticCommunity->save();

        $notification = ['message' => 'El idioma "' . $linguisticCommunity->name . '" se ha actualizado correctamente.', 'alert-type' => 'info'];
        return redirect()->route('linguistic-communities.index')->with(compact('notification'));
    }

    public function destroy(LinguisticCommunity $linguisticCommunity)
    {
        $linguisticCommunity->is_active = false;
        $linguisticCommunity->save();

        $notification = ['message' => 'El idioma "' . $linguisticCommunity->name . '" ha sido desactivado.', 'alert-type' => 'warning'];
        return redirect()->route('linguistic-communities.index')->with(compact('notification'));
    }

    public function restore(LinguisticCommunity $linguisticCommunity)
    {
        $linguisticCommunity->is_active = true;
        $linguisticCommunity->save();

        $notification = ['message' => 'El idioma "' . $linguisticCommunity->name . '" ha sido reactivado.', 'alert-type' => 'success'];
        return redirect()->route('linguistic-communities.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        LinguisticCommunity::whereIn('id', $ids)->update(['is_active' => false]);
        return back()->with('success', count($ids) . ' idiomas han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        LinguisticCommunity::whereIn('id', $ids)->update(['is_active' => true]);
        return back()->with('success', count($ids) . ' idiomas han sido reactivados.');
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
        $pdf = Pdf::loadView('modules.patient.linguistic-communities.print', compact('items'));
        return $pdf->download('idiomas.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? LinguisticCommunity::whereIn('id', $ids)->orderBy('id')->get() : LinguisticCommunity::orderBy('id')->get();
        return view('modules.patient.linguistic-communities.print', compact('items'));
    }
}
