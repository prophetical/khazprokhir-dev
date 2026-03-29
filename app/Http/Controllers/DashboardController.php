<?php

namespace App\Http\Controllers;

use App\Models\HcsReceiving;
use App\Models\Pack;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(\App\Services\DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $data = $this->dashboardService->getDashboardData($request);
        return view('dashboard', $data);
    }
}
