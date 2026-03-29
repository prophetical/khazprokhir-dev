<?php

namespace App\Http\Controllers;

use App\Services\BatchTrackingService;
use Illuminate\Http\Request;

class BatchTrackingController extends Controller
{
    protected $service;

    public function __construct(BatchTrackingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->all();
        $tracking = $this->service->getTrackingData($filters);

        return view('batch-tracking.index', array_merge($filters, $tracking, [
            'search' => $request->input('search'),
            'startDate' => $request->input('start_date'),
            'endDate' => $request->input('end_date'),
            'pecahanFilter' => $request->input('pecahan')
        ]));
    }
}
