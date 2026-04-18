<?php

namespace App\Http\Controllers;
use App\Models\Auditoria;

class AuditoriaController extends Controller
{
    public function index()
    {
        // Obtenemos logs, ordenados por fecha descendente, con datos del usuario
        $logs = Auditoria::with('user')->latest()->paginate(20);
        return view('auditoria.index', compact('logs'));
    }
}