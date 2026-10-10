<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\GithubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $source = $request->get('source', 'all');

        // ── Local products (ручные / импортированные) ──
        $localProducts = Product::query()
            ->when(method_exists(Product::class, 'scopeActive'),
                fn ($q) => $q->active())
            ->when(in_array('sort_order', (new Product)->getFillable()),
                fn ($q) => $q->orderBy('sort_order'))
            ->when(in_array('is_featured', (new Product)->getFillable()),
                fn ($q) => $q->orderByDesc('is_featured'))
            ->orderByDesc('id')
            ->get();

        // ── GitHub repos ──
        $githubRepos = [];
        if ($source === 'all' || $source === 'github') {
            try {
                $githubRepos = Cache::remember('products.github', 1800, function () {
                    return app(GithubService::class)->repos(50);
                });
            } catch (\Throwable $e) {
                logger()->warning('[products] github failed', ['error' => $e->getMessage()]);
            }
        }

        // ── Фильтрация ──
        if ($source === 'manual') {
            $githubRepos = [];
        } elseif ($source === 'github') {
            $localProducts = collect();
        }

        $stats = [
            'local'  => $localProducts->count(),
            'github' => count($githubRepos),
            'total'  => $localProducts->count() + count($githubRepos),
        ];

        return view('products', compact('localProducts', 'githubRepos', 'source', 'stats'));
    }
}
