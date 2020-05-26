<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserAttachFavoriteMovieRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'note' => 'nullable',
            'rate' => 'nullable|integer|min:0',

        ];
    }
}
