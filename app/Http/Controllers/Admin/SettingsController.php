<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.settings.zoom-accounts.index');
    }

    public function general(): View
    {
        return view('admin.settings.general');
    }
}
