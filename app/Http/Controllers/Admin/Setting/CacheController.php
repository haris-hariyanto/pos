<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Cache') => '',
        ];

        return view('admin.settings.cache', compact('breadcrumb'));
    }

    public function flush()
    {
        Cache::flush();
        return redirect()->back()->with('success', __('Cache has been flushed!'));
    }
}
