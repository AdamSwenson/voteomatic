<?php


namespace App\LTI\Authenticators;


use App\Http\Requests\LTIRequest;
use App\LTI\Authenticators\LaunchAuthenticator;
use App\LTI\Authenticators\OAuthAuthenticator;

class AuthenticatorFactory
{

    public static function make( $request=null){

        return new OAuthAuthenticator();

        //check from the type of request

        if($request instanceof LTIRequest){

            switch($request->lti_message_type){
                case 'basic-lti-launch-request':
                    return new LaunchAuthenticator($request);
            }
        }


    }

}
