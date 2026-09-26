<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\AreaUsuario;
use App\Models\Role;

class PersonalSeeder extends Seeder
{
    public function run(): void
    {
        $personalRole = Role::where('nombre', 'Personal')->first();
        if (!$personalRole) {
            $this->command->error('Rol "Personal" no encontrado. Ejecuta RoleSeeder primero.');
            return;
        }

        $cargos = Cargo::whereIn('nombre', ['Alcalde', 'Procuradora', 'Gerente', 'Subgerente', 'Jefe de Oficina', 'Área Institucional'])->get()->keyBy('nombre');

        $personalData = [
            // DNI => [nombres, apellidos, telefono, designaciones[]]
            // Designacion: [area_nombre, cargo_nombre, fecha_inicio, tipo_designacion]
            
            '43323464' => [
                'nombres' => 'Murriel Montes Manuel Enrique',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Control Institucional', 'cargo' => 'Jefe de Oficina', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                    ['area' => 'Gerencia Municipal', 'cargo' => 'Jefe de Oficina', 'fecha' => '2024-03-07', 'tipo' => 'Titular'],
                ]
            ],
            '44775860' => [
                'nombres' => 'Juan Manuel Sanchez Huertas',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Programación Multianual de Inversiones', 'cargo' => 'Jefe de Oficina', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '08653985' => [
                'nombres' => 'Perez Mejia Cesar Leonardo',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Gerencia de Administración Tributaria', 'cargo' => 'Gerente', 'fecha' => '2019-01-02', 'tipo' => 'Titular'],
                    ['area' => 'Gerencia de Administración Tributaria', 'cargo' => 'Gerente', 'fecha' => '2023-04-01', 'tipo' => 'Titular'],
                ]
            ],
            '32130801' => [
                'nombres' => 'Luis Alberto Murriel Santolalla',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Ejecución Coactiva', 'cargo' => 'Subgerente', 'fecha' => '2001-09-10', 'tipo' => 'Titular'],
                ]
            ],
            '32131474' => [
                'nombres' => 'Jose Carlo Cespedes Quiñones',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Gestión del Riesgo de Desastres', 'cargo' => 'Subgerente', 'fecha' => '2019-01-21', 'tipo' => 'Titular'],
                ]
            ],
            '32100339' => [
                'nombres' => 'Efraín Fernando Lockuan Lavado',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Registro Civil y Separación Convencional', 'cargo' => 'Subgerente', 'fecha' => '2022-12-21', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Registro Civil y Separación Convencional', 'cargo' => 'Subgerente', 'fecha' => '2021-02-25', 'tipo' => 'Titular'],
                ]
            ],
            '32101952' => [
                'nombres' => 'Javier Nicanor Cabello Polo',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Desarrollo Territorial', 'cargo' => 'Subgerente', 'fecha' => '2026-02-25', 'tipo' => 'Titular'],
                ]
            ],
            '70899198' => [
                'nombres' => 'Pedro Deyvis Lopez Velasquez',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Contabilidad', 'cargo' => 'Jefe de Oficina', 'fecha' => '2026-06-01', 'tipo' => 'Titular'],
                ]
            ],
            '18147332' => [
                'nombres' => 'Tania Marcela Torres Saenz',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina General de Asesoría Jurídica', 'cargo' => 'Jefe de Oficina', 'fecha' => '2026-03-23', 'tipo' => 'Titular'],
                ]
            ],
            '32110351' => [
                'nombres' => 'César Adolfo Villafranca Chávez',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Fiscalización y Policía Municipal', 'cargo' => 'Subgerente', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '44027447' => [
                'nombres' => 'Wilfredo Nain Silva Mallqui',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Desarrollo Industrial Agropecuario y Pesquero', 'cargo' => 'Subgerente', 'fecha' => '2026-02-23', 'tipo' => 'Titular'],
                ]
            ],
            '07480940' => [
                'nombres' => 'Richer Fredy Norabuena Jacome',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Abastecimiento y Gestión Patrimonial', 'cargo' => 'Jefe de Oficina', 'fecha' => '2026-01-14', 'tipo' => 'Titular'],
                ]
            ],
            '32105076' => [
                'nombres' => 'Salinas Cano Neldvin',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Transporte y Seguridad Vial', 'cargo' => 'Subgerente', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '43590989' => [
                'nombres' => 'Alexis Ricardo Escobar Gil',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Fiscalización Ambiental y ATM', 'cargo' => 'Subgerente', 'fecha' => '2026-02-01', 'tipo' => 'Titular'],
                ]
            ],
            '08783482' => [
                'nombres' => 'Maria Jackeline Cabrera La Rosa',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Programas Sociales', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Servicios Sociales', 'cargo' => 'Subgerente', 'fecha' => '2026-01-07', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Participación Vecinal, Educación y Deportes', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Servicios Sociales', 'cargo' => 'Subgerente', 'fecha' => '2009-10-10', 'tipo' => 'Titular'],
                ]
            ],
            '46223837' => [
                'nombres' => 'Yholvi Cristian Alejo Minaya',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Desarrollo Turístico y Cultural', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Desarrollo Industrial Agropecuario y Pesquero', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Comercio, Licencias y Control Sanitario', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                ]
            ],
            '71820346' => [
                'nombres' => 'Martin Alejandro Colichon Calderon',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Transporte y Seguridad Vial', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Servicios Municipales', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Seguridad Ciudadana', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Gestión Ambiental', 'cargo' => 'Subgerente', 'fecha' => '2026-01-01', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Transporte y Seguridad Vial', 'cargo' => 'Subgerente', 'fecha' => '2024-09-05', 'tipo' => 'Encargado'],
                    ['area' => 'Gerencia de Servicios Municipales y Gestión Ambiental', 'cargo' => 'Gerente', 'fecha' => '2023-04-03', 'tipo' => 'Encargado'],
                ]
            ],
            '70920870' => [
                'nombres' => 'Niki D\'Angelo Higueras Konfu',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Control Institucional', 'cargo' => 'Jefe de Oficina', 'fecha' => '2025-09-26', 'tipo' => 'Titular'],
                ]
            ],
            '46715848' => [
                'nombres' => 'Hans Edgardo Ramos Chamorro',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Infraestructura', 'cargo' => 'Subgerente', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Infraestructura', 'cargo' => 'Subgerente', 'fecha' => '2024-01-08', 'tipo' => 'Titular'],
                ]
            ],
            '16745937' => [
                'nombres' => 'Octavio Verona Sánchez',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Tesorería', 'cargo' => 'Jefe de Oficina', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '04743097' => [
                'nombres' => 'Víctor Alayo Chicalla',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Fiscalización Ambiental y ATM', 'cargo' => 'Subgerente', 'fecha' => '2025-07-22', 'tipo' => 'Titular'],
                ]
            ],
            '43720055' => [
                'nombres' => 'Timoteo Macario Sata Maria Gabino',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Presupuesto', 'cargo' => 'Jefe de Oficina', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '44262641' => [
                'nombres' => 'Chavez Baylon Grober Wilfredo',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Abastecimiento y Gestión Patrimonial', 'cargo' => 'Área Institucional', 'fecha' => '2025-05-15', 'tipo' => 'Titular'],
                    ['area' => 'Oficina de Tecnologías de Información y Comunicaciones', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '40996382' => [
                'nombres' => 'Gilmer Ronald Castillo Chavez',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Infraestructura', 'cargo' => 'Área Institucional', 'fecha' => '2025-05-12', 'tipo' => 'Titular'],
                    ['area' => 'Gerencia de Desarrollo Territorial e Infraestructura', 'cargo' => 'Área Institucional', 'fecha' => '2024-01-08', 'tipo' => 'Titular'],
                ]
            ],
            '70353563' => [
                'nombres' => 'Yoice Marrelly Roque Huiza',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Contabilidad', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-14', 'tipo' => 'Titular'],
                ]
            ],
            '80253161' => [
                'nombres' => 'Jose Ricardo Llanos Colonia',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Servicios Municipales', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-12', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Servicios Municipales', 'cargo' => 'Área Institucional', 'fecha' => '2024-01-04', 'tipo' => 'Titular'],
                    ['area' => 'Oficina de Servicios Generales y Equipo Mecánico', 'cargo' => 'Área Institucional', 'fecha' => '2024-01-04', 'tipo' => 'Titular'],
                ]
            ],
            '42168243' => [
                'nombres' => 'Hernandez Malpica Charly James',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Planeamiento y Modernización', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-16', 'tipo' => 'Titular'],
                ]
            ],
            '41242454' => [
                'nombres' => 'Alan Tino Honores Vasquez',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Desarrollo Turístico y Cultural', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '42445089' => [
                'nombres' => 'Isaac Luis Blaz Ortiz',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Rentas', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-11', 'tipo' => 'Titular'],
                ]
            ],
            '48316946' => [
                'nombres' => 'Katherine Edith Chancafe Canchis',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Desarrollo Territorial', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-11', 'tipo' => 'Titular'],
                ]
            ],
            '43794859' => [
                'nombres' => 'Lyang Rosmery Miranda Falcón',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Transporte y Seguridad Vial', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-11', 'tipo' => 'Titular'],
                ]
            ],
            '45494427' => [
                'nombres' => 'Milagros Teresa Alegre Quito',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Programas Sociales', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '24699052' => [
                'nombres' => 'Gregory Armando Lopez Solis',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Seguridad Ciudadana', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '72691555' => [
                'nombres' => 'Joselin Lloysi Léon Paredes',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Fiscalización Ambiental y ATM', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-11', 'tipo' => 'Titular'],
                ]
            ],
            '70354264' => [
                'nombres' => 'Rhoydi Luvick Quiroz Santos',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Gestión Ambiental', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-11', 'tipo' => 'Titular'],
                ]
            ],
            '44089836' => [
                'nombres' => 'Gonzales Garcia Jose Frank',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Fiscalización y Policía Municipal', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-11', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Fiscalización y Policía Municipal', 'cargo' => 'Área Institucional', 'fecha' => '2018-01-05', 'tipo' => 'Titular'],
                ]
            ],
            '43482800' => [
                'nombres' => 'Montero Nuñez Susan Evelyn',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Participación Vecinal, Educación y Deportes', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                    ['area' => 'Subgerencia de Participación Vecinal, Educación y Deportes', 'cargo' => 'Área Institucional', 'fecha' => '2019-04-01', 'tipo' => 'Titular'],
                ]
            ],
            '45787353' => [
                'nombres' => 'Rommel Enrique Flores Vega',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Desarrollo Industrial Agropecuario y Pesquero', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-11', 'tipo' => 'Titular'],
                ]
            ],
            '73634385' => [
                'nombres' => 'Diego Rafael Santolalla Arias',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Comercio, Licencias y Control Sanitario', 'cargo' => 'Área Institucional', 'fecha' => '2025-03-11', 'tipo' => 'Titular'],
                ]
            ],
            '41599684' => [
                'nombres' => 'Paul Dagoberto Montero Quispe',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Servicios Sociales', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '41212315' => [
                'nombres' => 'Lavado Vera Karen Janeth',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia Local de Empadronamiento', 'cargo' => 'Área Institucional', 'fecha' => '2024-10-03', 'tipo' => 'Titular'],
                ]
            ],
            '41637460' => [
                'nombres' => 'Jorge Minchola Ortiz',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina General de Asesoría Jurídica', 'cargo' => 'Área Institucional', 'fecha' => '2024-07-05', 'tipo' => 'Titular'],
                ]
            ],
            '45598760' => [
                'nombres' => 'Alberto Jaime Tinoco Usua',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Subgerencia de Estudios y Proyectos', 'cargo' => 'Área Institucional', 'fecha' => '2026-02-19', 'tipo' => 'Titular'],
                ]
            ],
            '40355759' => [
                'nombres' => 'Miguel Angel Milla Cruz',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina General de Administración', 'cargo' => 'Área Institucional', 'fecha' => '2024-08-16', 'tipo' => 'Titular'],
                ]
            ],
            '32960944' => [
                'nombres' => 'Wilson Llerena Cecilia Veronica',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Procuraduría Pública Municipal', 'cargo' => 'Procuradora', 'fecha' => '2020-01-02', 'tipo' => 'Titular'],
                ]
            ],
            '17844952' => [
                'nombres' => 'Enrique Alfonso Abanto Diaz',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Archivo General', 'cargo' => 'Área Institucional', 'fecha' => '2011-08-17', 'tipo' => 'Titular'],
                ]
            ],
            '43355091' => [
                'nombres' => 'Melissa Cristina Guzmán Garro',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Relaciones Públicas e Imagen Institucional', 'cargo' => 'Área Institucional', 'fecha' => '2023-04-01', 'tipo' => 'Titular'],
                    ['area' => 'Oficina General de Atención al Ciudadano y Gestión Documentaria', 'cargo' => 'Área Institucional', 'fecha' => '2023-03-01', 'tipo' => 'Titular'],
                ]
            ],
            '32109596' => [
                'nombres' => 'Julio Cesar Melendez Lazaro',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Alcaldía', 'cargo' => 'Alcalde', 'fecha' => '2023-01-03', 'tipo' => 'Titular'],
                ]
            ],
            '42862472' => [
                'nombres' => 'Junior Maycol Acosta Salgado',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Abastecimiento y Gestión Patrimonial', 'cargo' => 'Área Institucional', 'fecha' => '2023-04-01', 'tipo' => 'Titular'],
                ]
            ],
            '40804277' => [
                'nombres' => 'Carlos Jose Peje Quesada',
                'apellidos' => '',
                'telefono' => '',
                'designaciones' => [
                    ['area' => 'Oficina de Control Institucional', 'cargo' => 'Área Institucional', 'fecha' => '2023-04-01', 'tipo' => 'Titular'],
                ]
            ],
        ];

        $createdUsers = 0;
        $createdDesignaciones = 0;

        foreach ($personalData as $dni => $data) {
            $user = User::where('dni', $dni)->where('rol_id', $personalRole->id)->first();

            if (!$user) {
                $user = User::create([
                    'nombres' => $data['nombres'],
                    'apellidos' => $data['apellidos'],
                    'name' => trim($data['nombres'] . ' ' . $data['apellidos']),
                    'dni' => $dni,
                    'telefono' => $data['telefono'],
                    'rol_id' => $personalRole->id,
                    'estado' => 'activo',
                    'actualmente_laborando' => true,
                    'fecha_ingreso' => collect($data['designaciones'])->min('fecha'),
                    'password' => null,
                    'email' => null,
                ]);
                $createdUsers++;
            }

            // Group designaciones by area, keep only the latest per area
            $latestByArea = collect($data['designaciones'])
                ->groupBy('area')
                ->map(function ($group) {
                    return $group->sortByDesc('fecha')->first();
                });

            foreach ($latestByArea as $desig) {
                $area = Area::where('nombre', $desig['area'])->first();
                $cargo = $cargos->get($desig['cargo']);

                if (!$area) {
                    $this->command->warn("Area no encontrada: {$desig['area']} para DNI {$dni}");
                    continue;
                }

                if (!$cargo) {
                    $this->command->warn("Cargo no encontrado: {$desig['cargo']} para DNI {$dni}");
                    continue;
                }

                $exists = AreaUsuario::where('usuario_id', $user->id)
                    ->where('area_id', $area->id)
                    ->exists();

                if (!$exists) {
                    AreaUsuario::create([
                        'area_id' => $area->id,
                        'usuario_id' => $user->id,
                        'cargo_id' => $cargo->id,
                        'cargo' => $cargo->nombre,
                        'tipo_designacion' => $desig['tipo'],
                        'usuario_designador_id' => 1,
                        'observaciones' => 'Carga inicial desde PersonalSeeder',
                        'fecha_asignacion' => $desig['fecha'],
                        'fecha_inicio' => $desig['fecha'],
                        'activo' => true,
                        'estado_asignacion' => 'activo',
                    ]);
                    $createdDesignaciones++;
                }
            }
        }

        $this->command->info("Personal creado: {$createdUsers} usuarios, {$createdDesignaciones} designaciones.");
    }
}