<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Evento;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // 1. Número de contratos confirmados.
        // Asumiendo que un contrato se considera confirmado si el Evento ligado tiene estado 'confirmado'
        $contratosConfirmadosQuery = Contrato::whereHas('evento', function ($query) {
            $query->where('estado', 'confirmado');
        });

        $cantidadConfirmados = $contratosConfirmadosQuery->count();

        // 2. Ingresos proyectados (Suma del monto_total de los contratos confirmados)
        $ingresosTotales = $contratosConfirmadosQuery->sum('monto_total');

        return view('dashboard', [
            'cantidadConfirmados' => $cantidadConfirmados,
            'ingresosTotales' => $ingresosTotales
        ]);
    }
}
