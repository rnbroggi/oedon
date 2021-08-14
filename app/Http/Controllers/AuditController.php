<?php

namespace App\Http\Controllers;

use App\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['name' => "Auditoría"]
        ];

        $audits = Audit::with('user')
                ->select('id', 'user_id', 'event', 'auditable_type', 'auditable_id', 'old_values', 'new_values', 'created_at')
                ->orderBy('created_at', 'DESC')
                ->take(200)
                ->get();

        return view('audits.index', compact('audits', 'breadcrumbs'));
    }
}
