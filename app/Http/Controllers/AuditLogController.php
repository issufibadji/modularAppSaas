<?php

namespace App\Http\Controllers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->can('audit-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   
        // Inicializar a consulta
        $query = Audit::query();

        // Aplicar filtro de evento, se presente
        if ($request->filled('event')) {
            $query->where('event', $request->input('event'));
        }

        // Aplicar filtro de user_id, se presente
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        // Buscar os registros de auditoria com os filtros aplicados
        $audits = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('audit.index', compact('audits'));
    }
}