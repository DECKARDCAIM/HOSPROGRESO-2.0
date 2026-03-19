<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'name'        => 'Diario (Lunes a Viernes)',
                'type'        => '8',
                'description' => 'Horario de lunes a viernes de 8:00AM a 4:00PM',
                'days_hours'  => [
                    'monday'    => '08:00-16:00',
                    'tuesday'   => '08:00-16:00',
                    'wednesday' => '08:00-16:00',
                    'thursday'  => '08:00-16:00',
                    'friday'    => '08:00-16:00',
                    'saturday'  => null,
                    'sunday'    => null,
                ],
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
