<?php

namespace App\Http\Controllers;

use App\Models\Disability;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDisabilityRequest;
use App\Http\Requests\UpdateDisabilityRequest;
use App\Exports\DisabilityExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DisabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
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

        $perPage        = $request->input('per_page', 25);
        $allFilteredIds = (clone $query)->pluck('id')->toArray();
        $items          = $query->orderBy('id')->paginate($perPage)->appends($request->query());

        $total    = Disability::count();
        $active   = Disability::where('is_active', true)->count();
        $inactive = Disability::where('is_active', false)->count();

        return view('modules.patient.disabilities.index', compact('items', 'allFilteredIds', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('modules.patient.disabilities.create');
    }

    public function store(StoreDisabilityRequest $request)
    {
        $item = new Disability();
        $item->name = $request->input('name');
        $item->save();

        $notification = ['message' => 'La discapacidad "' . $item->name . '" se ha creado correctamente.', 'alert-type' => 'success'];
        return redirect()->route('disabilities.index')->with(compact('notification'));
    }

    public function show(Disability $disability) {}

    public function edit(Disability $disability)
    {
        return view('modules.patient.disabilities.edit', ['item' => $disability]);
    }

    public function update(UpdateDisabilityRequest $request, Disability $disability)
    {
        $disability->name = $request->input('name');
        $disability->save();

        $notification = ['message' => 'La discapacidad "' . $disability->name . '" se ha actualizado correctamente.', 'alert-type' => 'info'];
        return redirect()->route('disabilities.index')->with(compact('notification'));
    }

    public function destroy(Disability $disability)
    {
        $disability->is_active = false;
        $disability->save();

        $notification = ['message' => 'La discapacidad "' . $disability->name . '" ha sido desactivada.', 'alert-type' => 'warning'];
        return redirect()->route('disabilities.index')->with(compact('notification'));
    }

    public function restore(Disability $disability)
    {
        $disability->is_active = true;
        $disability->save();

        $notification = ['message' => 'La discapacidad "' . $disability->name . '" ha sido reactivada.', 'alert-type' => 'success'];
        return redirect()->route('disabilities.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        Disability::whereIn('id', $ids)->update(['is_active' => false]);
        return back()->with('success', count($ids) . ' discapacidades han sido desactivadas.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        Disability::whereIn('id', $ids)->update(['is_active' => true]);
        return back()->with('success', count($ids) . ' discapacidades han sido reactivadas.');
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
        $pdf = Pdf::loadView('modules.patient.disabilities.print', compact('items'));
        return $pdf->download('discapacidades.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Disability::whereIn('id', $ids)->orderBy('id')->get() : Disability::orderBy('id')->get();
        return view('modules.patient.disabilities.print', compact('items'));
    }
}
