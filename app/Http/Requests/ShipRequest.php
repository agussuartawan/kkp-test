<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => 'required',
            'name' => 'required',
            'owner_name' => 'required',
            'owner_address' => 'required',
            'ship_size' => 'required',
            'captain_name' => 'required',
            'member_size' => 'required',
            'photo' => 'required',
            'licence_number' => 'required',
            'licence_doc' => 'required'
        ];
    }
}
