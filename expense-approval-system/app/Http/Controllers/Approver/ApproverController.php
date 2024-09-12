<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproverRequest;
use App\Services\Approver\ApproverService;

class ApproverController extends Controller
{
    protected $approverService;

    /**
     * Inject ApproverService into the controller.
     *
Controller
     * @param \App\Services\Approver\ApproverService $approverService
     */
    public function __construct(ApproverService $approverService)
    {
        $this->approverService = $approverService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ApproverRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApproverRequest $request)
    {
        $approver = $this->approverService->store($request->validated());

        return response()->json($approver, 201);
    }
}
