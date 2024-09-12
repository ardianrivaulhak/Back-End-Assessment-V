<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovalStageRequest extends FormRequest
{
    // public function authorize()
    // {
    //     return true;
    // }

    public function rules()
    {
        $approvalStageId = $this->route('approval_stage');

        return [
            'approver_id' => [
                'required',
                'exists:approvers,id',
                Rule::unique('approval_stages', 'approver_id')->ignore($approvalStageId),
            ],
        ];
    }
}