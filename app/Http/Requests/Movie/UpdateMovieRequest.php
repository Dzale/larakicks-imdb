<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Enums\Movie\MovieGenres;

class UpdateMovieRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => 'required|max:255',
            'genre' => 'required|in:'.MovieGenres::stringify(),
            'release_date' => 'nullable|date',

        ];
    }
}
