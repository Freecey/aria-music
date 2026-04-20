<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ApiLog::with('user')->latest();

        if ($request->method) {
            $query->where('method', $request->method);
        }
        if ($request->endpoint) {
            $query->where('endpoint', 'like', '%' . $request->endpoint . '%');
        }

        $logs = $query->paginate(30)->withQueryString();
        return view('admin.logs.index', compact('logs'));
    }
}
