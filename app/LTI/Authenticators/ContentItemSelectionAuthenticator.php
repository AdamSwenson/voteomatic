<?php


namespace App\LTI\Authenticators;


use App\Http\Requests\LTIRequest;
use App\Models\ResourceLink;

class ContentItemSelectionAuthenticator  implements IAuthenticator
{

//    public function checkRequired($request){
//
//        if ($_POST['lti_message_type'] === 'ContentItemSelectionRequest') {
//            if (isset($_POST['accept_media_types']) && (strlen(trim($_POST['accept_media_types'])) > 0)) {
//                $mediaTypes = array_filter(explode(',', str_replace(' ', '', $_POST['accept_media_types'])), 'strlen');
//                $mediaTypes = array_unique($mediaTypes);
//                $this->ok = count($mediaTypes) > 0;
//                if (!$this->ok) {
//                    $this->reason = 'No accept_media_types found.';
//                } else {
//                    $this->mediaTypes = $mediaTypes;
//                }
//            } else {
//                $this->ok = false;
//            }
//
//            if ($this->ok) {
//                $this->ok = isset($_POST['content_item_return_url']) && (strlen(trim($_POST['content_item_return_url'])) > 0);
//                if (!$this->ok) {
//                    $this->reason = 'Missing content_item_return_url parameter.';
//                }
//            }
//    }

    public function authenticate(LTIRequest $request, ResourceLink $resourceLink)
    {
        // TODO: Implement authenticate() method.
    }
}
