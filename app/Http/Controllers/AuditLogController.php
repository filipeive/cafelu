<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('auditable_type', 'like', "%{$search}%")
                    ->orWhere('new_values', 'like', "%{$search}%")
                    ->orWhere('old_values', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20);
        $users = User::all();

        return view('audit_logs.index', compact('logs', 'users'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('audit_logs.show', compact('auditLog'));
    }
}
