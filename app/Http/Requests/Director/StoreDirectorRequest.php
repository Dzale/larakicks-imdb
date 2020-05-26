<?php

namespace App\Http\Requests\Director;

use Illuminate\Foundation\Http\FormRequest;

class StoreDirectorRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'dob' => 'nullable|date',

        ];
    }
}
