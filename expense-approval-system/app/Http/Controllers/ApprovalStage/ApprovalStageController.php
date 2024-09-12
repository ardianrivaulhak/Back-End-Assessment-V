<?php

namespace App\Http\Controllers\ApprovalStage;

use App\Http\Requests\ApprovalStageRequest;
use App\Services\ApprovalStage\ApprovalStageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovalStageController extends Controller
{
    protected $approvalStageService;

    public function __construct(ApprovalStageService $approvalStageService)
    {
        $this->approvalStageService = $approvalStageService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ApprovalStageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApprovalStageRequest $request)
    {
        $stage = $this->approvalStageService->store($request->validated());

        return response()->json($stage, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $stage = $this->approvalStageService->update($id, $request->all());

        return response()->json($stage);
    }
}
