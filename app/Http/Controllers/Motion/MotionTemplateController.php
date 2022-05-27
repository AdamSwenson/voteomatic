<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\Controller;
use App\Models\Motion;
use App\Repositories\MotionTemplateRepository;
use Illuminate\Http\Request;

class MotionTemplateController extends Controller
{


    public function getTemplates(){
        //dev This is where we control whether to show the prefab motion buttons for demonstration purposes
        if(env('APP_ENV') !== null && env('APP_ENV') === 'local') {
            $d = array_merge(MotionTemplateRepository::$templates, MotionTemplateRepository::$introTemplates);
        }else{
            $d = MotionTemplateRepository::$templates;
        }

            return response()->json($d);
//
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
