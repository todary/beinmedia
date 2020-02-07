<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NameTranslate extends Model
{
    use SoftDeletes;

    protected $table        = "name_translate";

    protected $primaryKey   = "name_translate_id";

    protected $dates        = ["deleted_at"];

    protected $fillable     =
        [
            "name_id", "lang_id", "name_translate"
        ];

    public static $default_lang_id = 1;

}
