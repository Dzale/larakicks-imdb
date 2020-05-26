<?php

namespace App\Models\Enums\Movie;

use App\Support\AbstractEnum;

class MovieGenres extends AbstractEnum
{
    const _ACTION = 'action';
    const _COMEDY = 'comedy';
    const _HORROR = 'horror';
    const _CRIME = 'crime';
    const _DRAMA = 'drama';
    const _MYSTERY = 'mystery';
    const _FANTASY = 'fantasy';
    const _THRILLER = 'thriller';
}
