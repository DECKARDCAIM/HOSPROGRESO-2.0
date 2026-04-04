<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Department;
use App\Models\Municipality;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MetricsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function system()
    {
        $data = $this->getCachedSystemMetrics();

        return view('modules.Metrics.System.index', $data);
    }

    public function expandSystem(Request $request, $chartId)
    {
        $chartNames = [
            'chart1' => 'Métricas de Ubicaciones',
        ];

        $cachedData = $this->getCachedSystemMetrics();
        $locationsData = $cachedData['locationsData'];

        return view('modules.Metrics.System.expand', compact('chartId', 'chartNames', 'locationsData'));
    }

    public function exportPDF(Request $request)
    {
        $chartImage = $request->input('chart_image');
        $chartId = $request->input('chart_id', 'chart1');
        $chartTitle = 'Métricas del Sistema';

        $pdf = Pdf::loadView('modules.Metrics.System.print', compact('chartImage', 'chartId', 'chartTitle'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('metricas-'.$chartId.'-'.date('Ymd_His').'.pdf');
    }

    public function print(Request $request)
    {
        $chartImage = $request->input('chart_image');
        $chartId = $request->input('chart_id', 'chart1');
        $chartTitle = 'Métricas del Sistema';

        return view('modules.Metrics.System.print', compact('chartImage', 'chartId', 'chartTitle'));
    }

    private function getCachedSystemMetrics()
    {
        return Cache::tags(['countries', 'departments', 'municipalities'])->remember('system_metrics_data', now()->addHours(6), function () {

            $countriesActive = Country::where('is_active', true)->count();
            $countriesInactive = Country::where('is_active', false)->count();
            $countriesTotal = $countriesActive + $countriesInactive;

            $departmentsActive = Department::where('is_active', true)->count();
            $departmentsInactive = Department::where('is_active', false)->count();
            $departmentsTotal = $departmentsActive + $departmentsInactive;

            $municipalitiesActive = Municipality::where('is_active', true)->count();
            $municipalitiesInactive = Municipality::where('is_active', false)->count();
            $municipalitiesTotal = $municipalitiesActive + $municipalitiesInactive;

            $locationsData = [
                [
                    'type' => 'Países',
                    'active' => $countriesActive,
                    'inactive' => $countriesInactive,
                    'total' => $countriesTotal,
                ],
                [
                    'type' => 'Departamentos',
                    'active' => $departmentsActive,
                    'inactive' => $departmentsInactive,
                    'total' => $departmentsTotal,
                ],
                [
                    'type' => 'Municipios',
                    'active' => $municipalitiesActive,
                    'inactive' => $municipalitiesInactive,
                    'total' => $municipalitiesTotal,
                ],
            ];

            return compact(
                'countriesTotal', 'countriesInactive',
                'departmentsTotal', 'departmentsInactive',
                'municipalitiesTotal', 'municipalitiesInactive',
                'locationsData'
            );
        });
    }
}
