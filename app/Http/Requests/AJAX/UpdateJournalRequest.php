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
            'posting_date' => 'required|date',
            'memo' => 'nullable|string|max:255',
            'status' => 'required|in:posted',
        ];
    }
}