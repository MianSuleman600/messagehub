<?php

namespace App\Domain\Reports\Controllers;

use App\Domain\Reports\Services\ReportService;
use Illuminate\Http\Request;

class ReportController
{
    public function __construct(private ReportService $service) {}

    /**
     * Show the main dashboard with summary metrics
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $summary = $this->service->summary();

        return view('dashboard.index', compact('summary'));
    }

    /**
     * Display the reports index with optional date filters
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $summary = $this->service->summary(
            $request->input('from'),
            $request->input('to')
        );

        return view('reports.index', compact('summary'));
    }
}
