<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatesummationRequest extends FormRequest
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
            'destination_user_id' => 'require|integer',
            'sender_user_id' => 'require|integer',
            'total' => 'require|numeric',
            'rate_per_unit' => 'require|numeric',
            'bank' => 'require|string',
            'acount_number' => 'require|string',
            'transfer_fees' => 'require|numaric',   
            'processed_by' => 'require|string'
        ];
    }
}
