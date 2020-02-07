<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Name extends Model
{
    use SoftDeletes;

    protected $table        = "name";

    protected $primaryKey   = "name_id";

    protected $dates        = ["deleted_at"];

    protected $fillable     =
        [
            "name", "hits"
        ];

    public static $default_lang_id = 1;


    public function nameTranslates()
    {
        return $this->hasMany('App\NameTranslate','name_id','name_id');
    }

}
