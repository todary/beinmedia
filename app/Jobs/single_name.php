<?php

namespace App\Jobs;

use App\Language;
use App\Name;
use App\NameTranslate;
use App\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use LanguageDetector\LanguageDetector;
use Stichoza\GoogleTranslate\GoogleTranslate;

class single_name implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;
    public $hit;
    public $language;


    public function __construct($name,$hit)
    {

        $this->name = $name;;
        $this->hit = $hit;;
        $this->language =Language::all();

    }


    public function handle()
    {

        $nameData = $this->name;

        $get_name = NameTranslate::where("name_translate",$nameData)->get()->toArray();


        if(!empty($get_name)){


            $get_name = $get_name[0];

            Name::where('name_id', $get_name['name_id'])->increment('hits', 1);

        } else {

            $languageScores = LanguageDetector::detect($nameData)->getScores();


            if($languageScores['en'] > $languageScores['ar']){
                $translationData['en']  = $nameData;
                $translationData['ar']  =  GoogleTranslate::trans($nameData, 'ar', 'en');

            }else {
                $translationData['ar'] = $nameData;
                $translationData['en'] =  GoogleTranslate::trans($nameData, 'en', 'ar');
            }


            $data = [];
            $data['hits'] =$this->hit;

            $check = Name::create($data);

            $insertData = [];
            foreach($this->language as $lang_key => $lang_item) {
                $inputs = [];
                $inputs["name_id"] = $check->name_id;
                $inputs["name_translate"] =  $translationData[$lang_item->shortcut];
                $inputs["lang_id"] = $lang_item->lang_id;
                $insertData[]=$inputs;

            }


            NameTranslate::insert($insertData);

        }


    }




}
