<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSpecialtyRequest;
use App\Http\Requests\UpdateSpecialtyRequest;
use App\Exports\SpecialtyExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SpecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
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

        $perPage        = $request->input('per_page', 25);
        $allFilteredIds = (clone $query)->pluck('id')->toArray();
        $items          = $query->orderBy('id')->paginate($perPage)->appends($request->query());

        $total    = Specialty::count();
        $active   = Specialty::where('is_active', true)->count();
        $inactive = Specialty::where('is_active', false)->count();

        return view('modules.medical.specialties.index', compact('items', 'allFilteredIds', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('modules.medical.specialties.create');
    }

    public function store(StoreSpecialtyRequest $request)
    {
        $item = new Specialty();
        $item->name = $request->input('name');
        $item->save();

        $notification = ['message' => 'La especialidad "' . $item->name . '" se ha creado correctamente.', 'alert-type' => 'success'];
        return redirect()->route('specialties.index')->with(compact('notification'));
    }

    public function show(Specialty $specialty) {}

    public function edit(Specialty $specialty)
    {
        return view('modules.medical.specialties.edit', ['item' => $specialty]);
    }

    public function update(UpdateSpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->name = $request->input('name');
        $specialty->save();

        $notification = ['message' => 'La especialidad "' . $specialty->name . '" se ha actualizado correctamente.', 'alert-type' => 'info'];
        return redirect()->route('specialties.index')->with(compact('notification'));
    }

    public function destroy(Specialty $specialty)
    {
        $specialty->is_active = false;
        $specialty->save();

        $notification = ['message' => 'La especialidad "' . $specialty->name . '" ha sido desactivada.', 'alert-type' => 'warning'];
        return redirect()->route('specialties.index')->with(compact('notification'));
    }

    public function restore(Specialty $specialty)
    {
        $specialty->is_active = true;
        $specialty->save();

        $notification = ['message' => 'La especialidad "' . $specialty->name . '" ha sido reactivada.', 'alert-type' => 'success'];
        return redirect()->route('specialties.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        Specialty::whereIn('id', $ids)->update(['is_active' => false]);
        return back()->with('success', count($ids) . ' especialidades han sido desactivadas.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        Specialty::whereIn('id', $ids)->update(['is_active' => true]);
        return back()->with('success', count($ids) . ' especialidades han sido reactivadas.');
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
        $pdf = Pdf::loadView('modules.medical.specialties.print', compact('items'));
        return $pdf->download('especialidades.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Specialty::whereIn('id', $ids)->orderBy('id')->get() : Specialty::orderBy('id')->get();
        return view('modules.medical.specialties.print', compact('items'));
    }
}
