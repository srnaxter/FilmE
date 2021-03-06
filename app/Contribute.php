<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa una persona que puede desempeñar diferentes papeles en una película.
 *
 * Class Contribute
 * @package App
 */
class Contribute extends Model {
    protected $fillable = ['name', 'lastName', 'slug', 'birth_date', 'nacionality'];

    public function actor() {
        return $this->belongsToMany(Film::class, 'actor_film', 'actor_id', 'film_id');
    }

    public function director() {
        return $this->belongsToMany(Film::class, 'director_film', 'director_id', 'film_id');
    }

    public function nationality(){
        return $this->belongsTo(Nationality::class);
    }

    public function numberOfContributions() {
        $likeActor = $this->actor()->count();
        $likeDirector = $this->director()->count();

        return $likeActor + $likeDirector;
    }

    public function getAge(){
        return Carbon::now()->diffInYears(Carbon::createFromFormat('Y-m-d',$this->birth_date));
    }

    public static function extractOrCreateByName($contributesName){
        $contributes = explode(",", $contributesName);
        array_walk($contributes, function (&$contribute) {
            $contribute = trim($contribute);
        });


        $contributes = array_diff($contributes, array(""));
        array_walk($contributes, function (&$contribute) {
            $contribute = trim($contribute);
            $contribute = Contribute::firstOrCreate(['name' => $contribute, 'slug' => str_slug($contribute, '-')]);
        });


        return $contributes;

    }

}
