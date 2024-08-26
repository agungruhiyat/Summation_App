<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoresummationRequest extends FormRequest
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
            "destination_user_id" => "required|integer",
            "sender_user_id" => "required|integer",
            "total"=> "numeric",
            "rate_per_unit" => "numeric",
            "bank" => "required|string",
            "account_number" => "required|integer",
            "transfer_fees" => "numeric",   
            "processed_by" => "required|string"
        ];
    }
}