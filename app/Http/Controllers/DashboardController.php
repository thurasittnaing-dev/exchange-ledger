<?php

namespace App\Http\Controllers;

use App\Filters\DashboardFilter;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $service
    ) {}

    public function dashboard(Request $request, DashboardFilter $filter): View
    {
        $options = $this->service->filterOptions();
        $filters = $filter->values();
        $analytics = $this->service->analytics($filter);

        return view('admin.dashboard.index', compact('options', 'filters', 'analytics'));
    }
}
