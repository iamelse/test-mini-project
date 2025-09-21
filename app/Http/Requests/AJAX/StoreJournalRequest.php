<?php

namespace App\Http\Requests\AJAX;

use Illuminate\Foundation\Http\FormRequest;

class StoreJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ref_no' => 'required|string|max:20|unique:journals,ref_no',
            'posting_date' => 'required|date',
            'memo' => 'nullable|string|max:255',
            'status' => 'required|in:posted',
            'created_by' => 'nullable|integer',
        ];
    }
}