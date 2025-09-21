<?php

namespace App\Http\Requests\AJAX;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ref_no' => 'required|string|max:255',
            'posting_date' => 'required|date',
            'memo' => 'nullable|string',
            'lines' => 'sometimes|array',
            'lines.*.account_id' => 'required|string',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
        ];
    }
}