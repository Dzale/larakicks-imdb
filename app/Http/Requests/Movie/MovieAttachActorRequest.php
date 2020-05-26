<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Enums\MoviesActor\MoviesActorRoleTypes;

class MovieAttachActorRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'role' => 'required|max:255',
            'role_type' => 'required|in:'.MoviesActorRoleTypes::stringify(),

        ];
    }
}
