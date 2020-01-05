<?php

namespace App\Models;

use App\Events\ImageDeleted;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Image extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'images';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['src', 'country_id', 'preference_id'];
    protected $dispatchesEvents = [
        'deleted' => ImageDeleted::class,
    ];

    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'image_tours')->withTimestamps();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function preference()
    {
        return $this->belongsTo(Preference::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
