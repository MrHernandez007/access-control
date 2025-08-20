<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Residente;
use App\Models\Pago;

class GenerarPagosMensuales extends Command
{
    protected $signature = 'pagos:generar';
    protected $description = 'Genera pagos mensuales por mantenimiento para todos los residentes';

    public function handle()
    {
        $residentes = Residente::all();
        $mesActual = now()->format('Y-m');

        foreach ($residentes as $residente) {
            $existePago = Pago::where('residente_id', $residente->_id)
                ->where('mes', $mesActual)
                ->exists();

            if (!$existePago) {
                Pago::create([
                    'residente_id' => $residente->_id,
                    'vivienda_id' => $residente->vivienda_id ?? null,
                    'mes' => $mesActual,
                    'concepto' => 'Cuota de mantenimiento',
                    'monto' => 1200,
                    'estado' => 'pendiente',
                    'fecha_generado' => now(),
                ]);
            }
        }

        $this->info('Pagos mensuales generados con éxito.');
    }
}

