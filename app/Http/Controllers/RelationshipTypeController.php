<?php

namespace App\Http\Controllers;

use App\Exports\RelationshipTypeExport;
use App\Http\Requests\StoreRelationshipTypeRequest;
use App\Http\Requests\UpdateRelationshipTypeRequest;
use App\Models\RelationshipType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class RelationshipTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cacheKey = 'relationship_types_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['relationship_types'])->remember($cacheKey, now()->addDays(1), function () use ($request) {
            $query = RelationshipType::query();

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
            $relationshipTypes = $query->orderBy('id')->paginate($perPage)->appends($request->query());

            $totalCount = RelationshipType::count();
            $activeCount = RelationshipType::where('is_active', true)->count();
            $inactiveCount = RelationshipType::where('is_active', false)->count();

            return compact('relationshipTypes', 'allFilteredIds', 'totalCount', 'activeCount', 'inactiveCount');
        });

        return view('modules.maintenance.relationship-types.index', $data);
    }

    public function create()
    {
        return view('modules.maintenance.relationship-types.create');
    }

    public function store(StoreRelationshipTypeRequest $request)
    {
        $item = RelationshipType::create($request->validated());

        $notification = [
            'message' => 'El tipo de relación '.$item->name.' se ha creado correctamente.',
            'alert-type' => 'success',
        ];

        return redirect()->route('relationship-types.index')->with(compact('notification'));
    }

    public function show(RelationshipType $relationshipType) {}

    public function edit(RelationshipType $relationshipType)
    {
        return view('modules.maintenance.relationship-types.edit', compact('relationshipType'));
    }

    public function update(UpdateRelationshipTypeRequest $request, RelationshipType $relationshipType)
    {
        $relationshipType->update($request->validated());

        $notification = [
            'message' => 'El tipo de relación '.$relationshipType->name.' se ha actualizado correctamente.',
            'alert-type' => 'info',
        ];

        return redirect()->route('relationship-types.index')->with(compact('notification'));
    }

    public function destroy(RelationshipType $relationshipType)
    {
        $relationshipType->update(['is_active' => false]);

        $notification = [
            'message' => 'El tipo de relación '.$relationshipType->name.' ha sido desactivado correctamente.',
            'alert-type' => 'warning',
        ];

        return redirect()->route('relationship-types.index')->with(compact('notification'));
    }

    public function restore(RelationshipType $relationshipType)
    {
        $relationshipType->update(['is_active' => true]);

        $notification = [
            'message' => 'El tipo de relación '.$relationshipType->name.' ha sido reactivado correctamente.',
            'alert-type' => 'success',
        ];

        return redirect()->route('relationship-types.index')->with(compact('notification'));
    }

    public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        RelationshipType::whereIn('id', $ids)->update(['is_active' => false]);

        // Flush manual necesario para las consultas masivas
        Cache::tags(['relationship_types'])->flush();

        return back()->with('success', count($ids).' registros han sido desactivados.');
    }

    public function restoreMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return back()->with('error', 'No se seleccionaron registros.');
        }

        RelationshipType::whereIn('id', $ids)->update(['is_active' => true]);
        Cache::tags(['relationship_types'])->flush();

        return back()->with('success', count($ids).' registros han sido reactivados.');
    }

    public function exportExcel(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new RelationshipTypeExport($ids), 'tipos_relacion.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        return Excel::download(new RelationshipTypeExport($ids), 'tipos_relacion.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPDF(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? RelationshipType::whereIn('id', $ids)->orderBy('id')->get() : RelationshipType::orderBy('id')->get();
        $pdf = Pdf::loadView('modules.maintenance.relationship-types.print', compact('items'));

        return $pdf->download('tipos_relacion.pdf');
    }

    public function print(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $items = count($ids) > 0 ? RelationshipType::whereIn('id', $ids)->orderBy('id')->get() : RelationshipType::orderBy('id')->get();

        return view('modules.maintenance.relationship-types.print', compact('items'));
    }
}
