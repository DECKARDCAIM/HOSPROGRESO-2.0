<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Municipality;

class MunicipalitySeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all()->keyBy('name');
        
        if ($departments->isEmpty()) {
            $this->command->error('Departamentos no encontrados. Ejecute primero DepartmentSeeder.');
            return;
        }

        $municipalities = [
            'Guatemala' => [
                'Guatemala', 'Mixco', 'Villa Nueva', 'San Miguel Petapa', 'Villa Canales',
                'San Juan Sacatepéquez', 'Chinautla', 'Amatitlán', 'San José Pinula', 'Santa Catarina Pinula',
                'San Pedro Ayampuc', 'Palencia', 'Fraijanes', 'San Pedro Sacatepéquez', 'San Raymundo',
            ],
            'Sacatepéquez' => [
                'Antigua Guatemala', 'Ciudad Vieja', 'Jocotenango', 'Pastores', 'San Antonio Aguas Calientes',
                'San Bartolomé Milpas Altas', 'San Lucas Sacatepéquez', 'San Miguel Dueñas', 'Santa María de Jesús',
                'Santiago Sacatepéquez', 'Santo Domingo Xenacoj', 'Sumpango',
            ],
            'Escuintla' => [
                'Escuintla', 'Santa Lucía Cotzumalguapa', 'La Democracia', 'Siquinalá', 'Masagua',
                'Tiquisate', 'La Gomera', 'Guanagazapa', 'San José', 'Iztapa', 'Palín', 'San Vicente Pacaya',
            ],
            'Quetzaltenango' => [
                'Quetzaltenango', 'Salcajá', 'Olintepeque', 'San Carlos Sija', 'Sibilia',
                'Cabricán', 'Cajolá', 'San Miguel Sigüilá', 'Ostuncalco', 'San Mateo', 'Concepción Chiquirichapa',
                'San Martín Sacatepéquez', 'Almolonga', 'Cantel', 'Huitán', 'Zunil', 'Colomba',
            ],
            'Alta Verapaz' => [
                'Cobán', 'Santa Cruz Verapaz', 'San Cristóbal Verapaz', 'Tactic', 'Tamahú',
                'Tucurú', 'Panzós', 'Senahú', 'San Pedro Carchá', 'San Juan Chamelco',
                'Lanquín', 'Cahabón', 'Chisec', 'Chahal', 'Fray Bartolomé de las Casas',
            ],
            'Huehuetenango' => [
                'Huehuetenango', 'Chiantla', 'Malacatancito', 'Cuilco', 'Nentón',
                'San Pedro Necta', 'Jacaltenango', 'Soloma', 'Ixtahuacán', 'Santa Bárbara',
                'La Libertad', 'La Democracia', 'San Miguel Acatán', 'San Rafael La Independencia',
            ],
            'San Marcos' => [
                'San Marcos', 'San Pedro Sacatepéquez', 'San Antonio Sacatepéquez', 'Comitancillo',
                'San Miguel Ixtahuacán', 'Concepción Tutuapa', 'Tacaná', 'Sibinal', 'Tajumulco',
                'Tejutla', 'San Rafael Pie de la Cuesta', 'Nuevo Progreso',
            ],
            'Quiché' => [
                'Santa Cruz del Quiché', 'Chiché', 'Chinique', 'Zacualpa', 'Chajul',
                'San Juan Cotzal', 'Nebaj', 'San Andrés Sajcabajá', 'San Miguel Uspantán',
                'Sacapulas', 'San Bartolomé Jocotenango', 'Canillá', 'Chicamán',
            ],
            'Petén' => [
                'Flores', 'San José', 'San Benito', 'La Libertad', 'San Andrés',
                'Melchor de Mencos', 'Poptún', 'Dolores', 'San Luis', 'Sayaxché',
                'San Francisco', 'Santa Ana', 'Las Cruces',
            ],
            'Izabal' => [
                'Puerto Barrios', 'Livingston', 'El Estor', 'Morales', 'Los Amates',
            ],
            'Chiquimula' => [
                'Chiquimula', 'San José La Arada', 'San Juan Ermita', 'Jocotán', 'Camotán',
                'Olopa', 'Esquipulas', 'Concepción Las Minas', 'Quezaltepeque', 'San Jacinto',
            ],
            'Jutiapa' => [
                'Jutiapa', 'El Progreso', 'Santa Catarina Mita', 'Agua Blanca', 'Asunción Mita',
                'Yupiltepeque', 'Atescatempa', 'Jerez', 'El Adelanto', 'Zapotitlán',
            ],
            'Jalapa' => [
                'Jalapa', 'San Pedro Pinula', 'San Luis Jilotepeque', 'San Manuel Chaparrón',
                'San Carlos Alzatate', 'Monjas', 'Mataquescuintla',
            ],
            'El Progreso' => [
                'Guastatoya', 'Morazán', 'San Agustín Acasaguastlán', 'San Cristóbal Acasaguastlán',
                'El Jícaro', 'Sansare', 'Sanarate',
            ],
            'Zacapa' => [
                'Zacapa', 'Estanzuela', 'Río Hondo', 'Gualán', 'Teculután',
                'Usumatlán', 'Cabañas', 'San Diego', 'La Unión', 'Huité',
            ],
            'Chimaltenango' => [
                'Chimaltenango', 'San José Poaquil', 'San Martín Jilotepeque', 'Comalapa',
                'Santa Apolonia', 'Tecpán Guatemala', 'Patzún', 'Pochuta', 'Patzicía',
                'Santa Cruz Balanyá', 'Acatenango', 'Yepocapa', 'San Andrés Itzapa',
            ],
            'Sololá' => [
                'Sololá', 'San José Chacayá', 'Santa María Visitación', 'Santa Lucía Utatlán',
                'Nahualá', 'Santa Catarina Ixtahuacán', 'Santa Clara La Laguna', 'Concepción',
                'San Andrés Semetabaj', 'Panajachel', 'Santa Catarina Palopó', 'San Antonio Palopó',
            ],
            'Totonicapán' => [
                'Totonicapán', 'San Cristóbal Totonicapán', 'San Francisco El Alto',
                'San Andrés Xecul', 'Momostenango', 'Santa María Chiquimula',
                'Santa Lucía La Reforma', 'San Bartolo Aguas Calientes',
            ],
            'Retalhuleu' => [
                'Retalhuleu', 'San Sebastián', 'Santa Cruz Muluá', 'San Martín Zapotitlán',
                'San Felipe', 'San Andrés Villa Seca', 'Champerico', 'Nuevo San Carlos',
            ],
            'Suchitepéquez' => [
                'Mazatenango', 'Cuyotenango', 'San Francisco Zapotitlán', 'San Bernardino',
                'San José El Idolo', 'Santo Domingo Suchitepéquez', 'San Lorenzo', 'Samayac',
            ],
            'Baja Verapaz' => [
                'Salamá', 'San Miguel Chicaj', 'Rabinal', 'Cubulco', 'Granados',
                'Santa Cruz El Chol', 'San Jerónimo', 'Purulhá',
            ],
            'Santa Rosa' => [
                'Cuilapa', 'Barberena', 'Santa Rosa de Lima', 'Casillas', 'San Rafael Las Flores',
                'Oratorio', 'San Juan Tecuaco', 'Chiquimulilla', 'Taxisco', 'Santa María Ixhuatán',
            ],
        ];

        foreach ($municipalities as $deptName => $municipalityNames) {
            $department = $departments->get($deptName);
            
            if (!$department) {
                continue;
            }

            foreach ($municipalityNames as $municipalityName) {
                Municipality::create([
                    'department_id' => $department->id,
                    'name' => $municipalityName,
                    'is_active' => true,
                ]);
            }
        }
    }
}