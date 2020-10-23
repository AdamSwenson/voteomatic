<?php


namespace App\LTI\Authenticators;


use App\Http\Requests\LTIRequest;
use App\ResourceLink;

class PresentationDocAuthenticator  implements IAuthenticator
{

    public function checkRequired($request)
    {
        if ($this->ok && isset($_POST['accept_presentation_document_targets']) && (strlen(trim($_POST['accept_presentation_document_targets'])) > 0)) {
            $documentTargets = array_filter(explode(',', str_replace(' ', '', $_POST['accept_presentation_document_targets'])), 'strlen');
            $documentTargets = array_unique($documentTargets);
            $this->ok = count($documentTargets) > 0;
            if (!$this->ok) {
                $this->reason = 'Missing or empty accept_presentation_document_targets parameter.';
            } else {
                foreach ($documentTargets as $documentTarget) {
                    $this->ok = $this->checkValue($documentTarget, array('embed', 'frame', 'iframe', 'window', 'popup', 'overlay', 'none'),
                        'Invalid value in accept_presentation_document_targets parameter: %s.');
                    if (!$this->ok) {
                        break;
                    }
                }
                if ($this->ok) {
                    $this->documentTargets = $documentTargets;
                }
            }
        } else {
            $this->ok = false;
        }
    }

    public function authenticate(LTIRequest $request, ResourceLink $resourceLink)
    {
        // TODO: Implement authenticate() method.
    }
}