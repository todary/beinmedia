<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    protected $table        = "language";

    protected $primaryKey   = "lang_id";

    protected $dates        = ["deleted_at"];

    protected $fillable     =
        [
            "shortcut"
        ];

    public static $default_lang_id = 1;



}
