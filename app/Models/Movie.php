<?php

namespace App\Models;

use App\Models\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SearchTrait;
    
    /*
     * Items per page (default=100)
     */
    protected $perPage = 10;

    protected $table = "movies";

    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = [
        'actors', 'directors', 'favoritedUsers', 'wishlistedUsers'
    ];

    /**
     * The attributes by you can search data.
     *
     * @var array
     */
    protected $searchableColumns = [
        'id',
        'name',
        'genre',
        'release_date'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'genre',
        'release_date'
    ];

    /**
     * The attributes that are will be sent to front-end
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'genre',
        'release_date'
    ];

    public $timestamps = true;

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(
            Actor::class,
            'movies_actors',
            'movie_id',
            'actor_id'
        )->withTimestamps()->withPivot('role', 'role_type');
    }
    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(
            Director::class,
            'movies_directors',
            'movie_id',
            'director_id'
        )->withTimestamps();
    }
    public function favoritedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'favorite_movies',
            'movie_id',
            'user_id'
        )->withTimestamps()->withPivot('note', 'rate');
    }
    public function wishlistedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'wishlist_movies',
            'movie_id',
            'user_id'
        )->withTimestamps()->withPivot('note');
    }
}
