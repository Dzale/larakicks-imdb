<?php

namespace App\Models;

use App\Models\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SearchTrait;
    
    /*
     * Items per page (default=100)
     */
    protected $perPage = 10;

    protected $table = "comments";

    /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = [
        'creator'
    ];

    /**
     * The attributes by you can search data.
     *
     * @var array
     */
    protected $searchableColumns = [
        'id',
        'text',
        'rate'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id',
        'text',
        'rate'
    ];

    /**
     * The attributes that are will be sent to front-end
     *
     * @var array
     */
    protected $visible = [
        'id',
        'text',
        'rate'
    ];

    public $timestamps = true;

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
