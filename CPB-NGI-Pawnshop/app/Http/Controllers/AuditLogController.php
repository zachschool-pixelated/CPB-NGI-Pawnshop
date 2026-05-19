<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Show all audit logs (admin only)
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search in changes
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('model_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $auditLogs = $query->paginate(50);

        // Get distinct model types and users for filters
        $modelTypes = AuditLog::distinct('model_type')->pluck('model_type');
        $users = AuditLog::with('user')->distinct('user_id')->get()->pluck('user.name', 'user_id');

        return view('audit-logs.index', [
            'auditLogs' => $auditLogs,
            'modelTypes' => $modelTypes,
            'users' => $users,
            'filters' => [
                'action' => $request->action,
                'model_type' => $request->model_type,
                'user_id' => $request->user_id,
                'search' => $request->search,
            ]
        ]);
    }

    /**
     * Show detailed audit log entry
     */
    public function show(AuditLog $auditLog): View
    {
        return view('audit-logs.show', [
            'auditLog' => $auditLog,
        ]);
    }
}
