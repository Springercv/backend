<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Price extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'prices';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['tour_id', 'start_time', 'end_time', 'price'];
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
    public function tour()
    {
        return $this->belongsTo(Tour::class);
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
    public function getStartTimeAttribute($value)
    {
        return (int)convertTimeFormat($value, 'm');
    }

    public function getEndTimeAttribute($value)
    {
        return (int)convertTimeFormat($value, 'm');
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setStartTimeAttribute($value)
    {
        $value = getMonthOfYear()[$value] .  " 01 2000";
        $this->attributes['start_time'] = convertTimeFormat($value, 'Y-m-d');
    }

    public function setEndTimeAttribute($value)
    {
        $value = getMonthOfYear()[$value] .  " 01 2000";
        $this->attributes['end_time'] = convertTimeFormat($value, 'Y-m-d');
    }
}
