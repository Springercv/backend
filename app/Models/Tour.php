<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use ViralsBackpack\BackPackImageUpload\Traits\HasImages;

class Tour extends Model
{
    use CrudTrait;
    use HasImages;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'tours';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'start_date', 'end_date', 'vehicle', 'hotel_type', 'period_date', 'schedule', 'description', 'note'];
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
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'tour_locations')->withTimestamps();
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'tour_cities')->withTimestamps();
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'tour_countries')->withTimestamps();
    }

    public function preferences()
    {
        return $this->belongsToMany(Preference::class, 'tour_preferences');
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
