<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Department;
use App\Models\Municipality;

class MetricsController extends Controller
{


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
}
