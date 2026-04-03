<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Department;
use App\Models\Municipality;
use Barryvdh\DomPDF\Facade\Pdf;

class MetricsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function system()
    {
        $countriesTotal = Country::count();
        $countriesInactive = Country::where('is_active', false)->count();

        $departmentsTotal = Department::count();
        $departmentsInactive = Department::where('is_active', false)->count();

        $municipalitiesTotal = Municipality::count();
        $municipalitiesInactive = Municipality::where('is_active', false)->count();

        // data for charts
        $locationsData = [
            [
                'type' => 'Países',
                'active' => Country::where('is_active', true)->count(),
                'inactive' => $countriesInactive,
                'total' => $countriesTotal,
            ],
            [
                'type' => 'Departamentos',
                'active' => Department::where('is_active', true)->count(),
                'inactive' => $departmentsInactive,
                'total' => $departmentsTotal,
            ],
            [
                'type' => 'Municipios',
                'active' => Municipality::where('is_active', true)->count(),
                'inactive' => $municipalitiesInactive,
                'total' => $municipalitiesTotal,
            ]
        ];

        return view('modules.Metrics.System.index', compact(
            'countriesTotal',
            'countriesInactive',
            'departmentsTotal',
            'departmentsInactive',
            'municipalitiesTotal',
            'municipalitiesInactive',
            'locationsData'
        ));
    }

    public function expandSystem(Request $request, $chartId)
    {
        $chartNames = [
            'chart1' => 'Métricas de Ubicaciones'
        ];

        $locationsData = [
            [
                'type' => 'Países',
                'active' => Country::where('is_active', true)->count(),
                'inactive' => Country::where('is_active', false)->count(),
                'total' => Country::count(),
            ],
            [
                'type' => 'Departamentos',
                'active' => Department::where('is_active', true)->count(),
                'inactive' => Department::where('is_active', false)->count(),
                'total' => Department::count(),
            ],
            [
                'type' => 'Municipios',
                'active' => Municipality::where('is_active', true)->count(),
                'inactive' => Municipality::where('is_active', false)->count(),
                'total' => Municipality::count(),
            ]
        ];

        return view('modules.Metrics.System.expand', compact('chartId', 'chartNames', 'locationsData'));
    }

    public function exportPDF(Request $request)
    {
        $chartImage = $request->input('chart_image');
        $chartId = $request->input('chart_id', 'chart1');
        $chartTitle = 'Métricas del Sistema';

        $pdf = Pdf::loadView('modules.Metrics.System.print', compact('chartImage', 'chartId', 'chartTitle'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('metricas-' . $chartId . '-' . date('Ymd_His') . '.pdf');
    }

    public function print(Request $request)
    {
        $chartImage = $request->input('chart_image');
        $chartId = $request->input('chart_id', 'chart1');
        $chartTitle = 'Métricas del Sistema';

        return view('modules.Metrics.System.print', compact('chartImage', 'chartId', 'chartTitle'));
    }
}
