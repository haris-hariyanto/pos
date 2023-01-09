<?php

namespace App\Http\Controllers\Main\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemaps = [];

        return response()->view('main.misc.sitemap.sitemaps-index', compact('sitemaps'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapSample($index)
    {
        $urls = [
            // ['loc' => '', 'lastmod' => ''],
        ];

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }
}
