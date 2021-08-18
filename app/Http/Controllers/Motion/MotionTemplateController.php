<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\Controller;
use App\Models\Motion;
use App\Repositories\MotionTemplateRepository;
use Illuminate\Http\Request;

class MotionTemplateController extends Controller
{


    public function getTemplates(){
//        if(env('IS_DEMO') !== null && env('IS_DEMO') === true){
        $d = array_merge(MotionTemplateRepository::$templates , MotionTemplateRepository::$introTemplates);
//        $d = MotionTemplateRepository::$templates + MotionTemplateRepository::$introTemplates;
            return response()->json($d);
//        }
        return response()->json(MotionTemplateRepository::$templates);
    }


    public function getMotionTypes(){
        $out = [
            'amendment ' => Motion::$amendmentTypes,
            'procedural' => Motion::$proceduralTypes
        ];

        return response()->json($out);
    }


}
