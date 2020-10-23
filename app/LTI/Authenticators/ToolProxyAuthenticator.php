<?php


namespace App\LTI\Authenticators;


use App\Http\Requests\LTIRequest;
use App\Models\ResourceLink;

class ToolProxyAuthenticator  implements IAuthenticator
{

//    public function checkRequired($request)
//    {
//    } else if ($_POST['lti_message_type'] == 'ToolProxyRegistrationRequest') {
//$this->ok = ((isset($_POST['reg_key']) && (strlen(trim($_POST['reg_key'])) > 0)) &&
//(isset($_POST['reg_password']) && (strlen(trim($_POST['reg_password'])) > 0)) &&
//(isset($_POST['tc_profile_url']) && (strlen(trim($_POST['tc_profile_url'])) > 0)) &&
//(isset($_POST['launch_presentation_return_url']) && (strlen(trim($_POST['launch_presentation_return_url'])) > 0)));
//if ($this->debugMode && !$this->ok) {
//$this->reason = 'Missing message parameters.';
//}
//}
//
//    }
    public function authenticate(LTIRequest $request, ResourceLink $resourceLink)
    {
        // TODO: Implement authenticate() method.
    }
}
