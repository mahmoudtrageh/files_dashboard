<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->guard('admin')->user()->can('dashboard.view')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.pages.index');
    }
}
