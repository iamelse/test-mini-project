<?php

namespace App\Http\Requests\AJAX;

use Illuminate\Foundation\Http\FormRequest;

class StoreChartOfAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code'           => 'required|string|max:10|unique:chart_of_accounts,code',
            'name'           => 'required|string|max:100',
            'normal_balance' => 'required|in:DR,CR',
            'is_active'      => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'           => 'Code is required',
            'code.unique'             => 'Code must be unique',
            'name.required'           => 'Name is required',
            'normal_balance.required' => 'Normal balance is required',
            'normal_balance.in'       => 'Normal balance must be DR or CR',
        ];
    }
}
