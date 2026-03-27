<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGenderRequest;
use App\Http\Requests\UpdateGenderRequest;
use App\Exports\GenderExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class GenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Gender::query();

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

        $total    = Gender::count();
        $active   = Gender::where('is_active', true)->count();
        $inactive = Gender::where('is_active', false)->count();

        return view('modules.patient.genders.index', compact('items', 'allFilteredIds', 'total', 'active', 'inactive'));
    }

    public function create()
    {
        return view('modules.patient.genders.create');
    }

    public function store(StoreGenderRequest $request)
    {
        $item = new Gender();
        $item->name = $request->input('name');
        $item->save();

        $notification = ['message' => 'El género "' . $item->name . '" se ha creado correctamente.', 'alert-type' => 'success'];
        return redirect()->route('genders.index')->with(compact('notification'));
    }

    public function show(Gender $gender) {}

    public function edit(Gender $gender)
    {
        return view('modules.patient.genders.edit', ['item' => $gender]);
    }

    public function update(UpdateGenderRequest $request, Gender $gender)
    {
        $gender->name = $request->input('name');
        $gender->save();

        $notification = ['message' => 'El género "' . $gender->name . '" se ha actualizado correctamente.', 'alert-type' => 'info'];
        return redirect()->route('genders.index')->with(compact('notification'));
    }

    public function destroy(Gender $gender)
    {
        $gender->is_active = false;
        $gender->save();

        $notification = ['message' => 'El género "' . $gender->name . '" ha sido desactivado.', 'alert-type' => 'warning'];
        return redirect()->route('genders.index')->with(compact('notification'));
    }

    public function restore(Gender $gender)
    {
        $gender->is_active = true;
        $gender->save();

        $notification = ['message' => 'El género "' . $gender->name . '" ha sido reactivado.', 'alert-type' => 'success'];
        return redirect()->route('genders.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        Gender::whereIn('id', $ids)->update(['is_active' => false]);
        return back()->with('success', count($ids) . ' géneros han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        if (empty($ids)) return back()->with('error', 'No se seleccionaron registros.');
        Gender::whereIn('id', $ids)->update(['is_active' => true]);
        return back()->with('success', count($ids) . ' géneros han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        return Excel::download(new GenderExport($ids), 'generos.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        return Excel::download(new GenderExport($ids), 'generos.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Gender::whereIn('id', $ids)->orderBy('id')->get() : Gender::orderBy('id')->get();
        $pdf = Pdf::loadView('modules.patient.genders.print', compact('items'));
        return $pdf->download('generos.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? Gender::whereIn('id', $ids)->orderBy('id')->get() : Gender::orderBy('id')->get();
        return view('modules.patient.genders.print', compact('items'));
    }
}
