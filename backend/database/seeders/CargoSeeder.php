<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = [
            ['nombre' => 'Gerente Municipal', 'descripcion' => 'Máxima autoridad ejecutiva municipal', 'estado' => 'activo', 'unico' => true],
            ['nombre' => 'Gerente de Administración Tributaria', 'descripcion' => 'Gerencia de tributación y rentas', 'estado' => 'activo', 'unico' => true],
            ['nombre' => 'Gerente de Desarrollo Económico', 'descripcion' => 'Gerencia de desarrollo económico local', 'estado' => 'activo', 'unico' => true],
            ['nombre' => 'Gerente de Desarrollo Territorial e Infraestructura', 'descripcion' => 'Gerencia de infraestructura y territorio', 'estado' => 'activo', 'unico' => true],
            ['nombre' => 'Gerente de Servicios Municipales y Gestión Ambiental', 'descripcion' => 'Gerencia de servicios y ambiente', 'estado' => 'activo', 'unico' => true],
            ['nombre' => 'Gerente de Desarrollo Social', 'descripcion' => 'Gerencia de desarrollo social', 'estado' => 'activo', 'unico' => true],
            ['nombre' => 'Subgerente', 'descripcion' => 'Subgerencia de área funcional', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Jefe de Oficina', 'descripcion' => 'Jefatura de oficina administrativa', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Especialista', 'descripcion' => 'Personal técnico especializado', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Analista', 'descripcion' => 'Personal de análisis y soporte', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Asistente', 'descripcion' => 'Personal de apoyo administrativo', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Técnico', 'descripcion' => 'Personal técnico operativo', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Secretario/a', 'descripcion' => 'Apoyo administrativo y secretarial', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Coordinador', 'descripcion' => 'Coordinación de área o programa', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Director', 'descripcion' => 'Dirección de línea o programa', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Regidor', 'descripcion' => 'Autoridad edil concejal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Procurador Público Municipal', 'descripcion' => 'Defensa jurídica de la municipalidad', 'estado' => 'activo', 'unico' => true],
            ['nombre' => 'Inspector', 'descripcion' => 'Fiscalización y control municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Oficial Mayor', 'descripcion' => 'Mando administrativo superior', 'estado' => 'activo', 'unico' => true],
            ['nombre' => 'Administrador', 'descripcion' => 'Administración de recursos y servicios', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Contador', 'descripcion' => 'Contabilidad y finanzas municipales', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Abogado', 'descripcion' => 'Asesoría jurídica municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Ingeniero', 'descripcion' => 'Proyectos e infraestructura municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Arquitecto', 'descripcion' => 'Proyectos arquitectónicos municipales', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Economista', 'descripcion' => 'Planificación y presupuesto municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Licenciado en Administración', 'descripcion' => 'Gestión administrativa municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Licenciado en Derecho', 'descripcion' => 'Asuntos legales municipales', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Psicólogo', 'descripcion' => 'Programas sociales y bienestar', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Trabajador Social', 'descripcion' => 'Programas sociales y atención ciudadana', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Comunicador Social', 'descripcion' => 'Comunicación e imagen institucional', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Bibliotecólogo', 'descripcion' => 'Gestión documental y archivo', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Médico', 'descripcion' => 'Servicios de salud municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Enfermero', 'descripcion' => 'Servicios de salud municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Químico Farmacéutico', 'descripcion' => 'Farmacia y botiquines municipales', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Obstetra', 'descripcion' => 'Salud materno infantil municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Tecnólogo Médico', 'descripcion' => 'Laboratorio y diagnóstico municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Licenciado en Turismo', 'descripcion' => 'Promoción turística municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Guía de Turismo', 'descripcion' => 'Atención turística municipal', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Promotor Cultural', 'descripcion' => 'Actividades culturales municipales', 'estado' => 'activo', 'unico' => false],
            ['nombre' => 'Área Institucional', 'descripcion' => 'Usuario genérico de área institucional', 'estado' => 'activo', 'unico' => false],
        ];

        foreach ($cargos as $cargoData) {
            Cargo::firstOrCreate(
                ['nombre' => $cargoData['nombre']],
                $cargoData
            );
        }

        $this->command->info('Se crearon ' . count($cargos) . ' cargos correctamente.');
    }
}