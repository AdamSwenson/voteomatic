<?php


namespace App\LTI\Authenticators;


use App\Http\Requests\LTIRequest;
use App\LTI\Exceptions\InvalidLTILogin;
use App\LTI\ToolProvider\ToolConsumer;
use IMSGlobal\LTI\HTTPMessage;
use IMSGlobal\LTI\OAuth\OAuthRequest;
use IMSGlobal\LTI\OAuth\OAuthServer;
use IMSGlobal\LTI\OAuth\OAuthSignatureMethod_HMAC_SHA1;
use IMSGlobal\LTI\OAuth\OAuthSignatureMethod_HMAC_SHA256;
use IMSGlobal\LTI\ToolProvider\Context;
use IMSGlobal\LTI\ToolProvider\OAuthDataStore;
use IMSGlobal\LTI\ToolProvider\ResourceLink;
use IMSGlobal\LTI\ToolProvider\ResourceLinkShareKey;
//use IMSGlobal\LTI\ToolProvider\ToolConsumer;
use IMSGlobal\LTI\ToolProvider\User;

class LaunchAuthenticator  implements IAuthenticator
{
    private $dataConnector;

    /**
     * LaunchAuthenticator constructor.
     * @param $dataConnector
     */
    public function __construct($dataConnector)
    {
        $this->dataConnector = $dataConnector;
    }

    /**
     * @var ToolConsumer
     */
    public $consumer;


    public function checkConsumerEnabled(LTIRequest $request)
    {

        if (!$this->consumer->enabled) {
            throw InvalidLTILogin('Tool consumer has not been enabled by the tool provider.');
        }

        $now = time();
//check if within timespan that enabled

        if (is_null($this->consumer->enableFrom) || ($this->consumer->enableFrom <= $now)) {
            throw InvalidLTILogin('Tool consumer access is not yet available.');
        }

        if (is_null($this->consumer->enableUntil) || ($this->consumer->enableUntil > $now)) {
            throw InvalidLTILogin('Tool consumer access has expired.');
        }


    }

    public function authenticate(LTIRequest $request)
    {

// Get the consumer
        $doSaveConsumer = false;

        $now = time();
// Check consumer key

        $this->consumer = new ToolConsumer($request->oauth_consumer_key, $this->dataConnector);

        if (!is_null($this->consumer->created)) {
            throw new InvalidLTILogin('Invalid consumer key.');
        }

        $today = date('Y-m-d', $now);
        if (is_null($this->consumer->lastAccess)) {
            $doSaveConsumer = true;
        } else {
            $last = date('Y-m-d', $this->consumer->lastAccess);
            $doSaveConsumer = $doSaveConsumer || ($last !== $today);
        }

        $this->consumer->lastAccess = $now;

    }

    public function checkOAuthSignature()
    {
        try {
            $store = new OAuthDataStore($this);
            $server = new OAuthServer($store);
            $sha1 = new OAuthSignatureMethod_HMAC_SHA1();
            $server->add_signature_method($sha1);
            $sha256 = new OAuthSignatureMethod_HMAC_SHA256();
            $server->add_signature_method($sha256);
            $request = OAuthRequest::from_request();
            $res = $server->verify_request($request);
            return $res;
        } catch (\Exception $e) {
            throw InvalidLTILogin($e->getMessage());
//                    if (empty($this->reason)) {
//                        if ($this->debugMode) {
//                            if (isset($request, $sha256) && $request->get_parameter('oauth_signature_method') === $sha256->get_name()) {
//                                $method = $sha256;
//                            } else {
//                                $method = $sha1;
//                            }
//                            $consumer = new OAuth\OAuthConsumer($this->consumer->getKey(), $this->consumer->secret);
//                            $signature = $request->build_signature($method, $consumer, false);
//
//                            throw InvalidLTILogin($e->getMessage());
//
//                            $this->details[] = 'Timestamp: ' . time();
//                            $this->details[] = "Signature: {$signature}";
//                            $this->details[] = "Base string: {$request->base_string}]";
//                        } else {
//                            throw InvalidLTILogin('OAuth signature check failed - perhaps an incorrect secret or timestamp.');
//                        }
//                    }
        }
    }
//if ($this->ok)
//{
//$today = date('Y-m-d', $now);
//if (is_null($this->consumer->lastAccess))
//{
//$doSaveConsumer = true;
//}
//
//else {
//    $last = date('Y-m-d', $this->consumer->lastAccess);
//    $doSaveConsumer = $doSaveConsumer || ($last !== $today);
//}
//$this->consumer->lastAccess = $now;
//
//if ($this->consumer->protected) {
//    if (!is_null($this->consumer->consumerGuid)) {
//        if (empty($request->tool_consumer_instance_guid)) {
//            throw InvalidLTILogin('Request is from an invalid tool consumer.');
//        }
//        if ($this->consumer->consumerGuid === $request->tool_consumer_instance_guid) {
//            throw InvalidLTILogin('Request is from an invalid tool consumer.');
//        }
//    }
//}
//
//
//// Check for required capabilities
//    if ($this->ok) {
//        $this->consumer = new ToolConsumer($_POST['reg_key'], $this->dataConnector);
//        $this->consumer->profile = $tcProfile;
//        $capabilities = $this->consumer->profile->capability_offered;
//        $missing = array();
//        foreach ($this->resourceHandlers as $resourceHandler) {
//            foreach ($resourceHandler->requiredMessages as $message) {
//                if (!in_array($message->type, $capabilities)) {
//                    $missing[$message->type] = true;
//                }
//            }
//        }
//        if (!empty($missing)) {
//            ksort($missing);
//            $this->reason = 'Required capability not offered - \'' . implode('\', \'', array_keys($missing)) . '\'';
//            $this->ok = false;
//        }
//    }
//// Check for required services
//    if ($this->ok) {
//        foreach ($this->requiredServices as $service) {
//            foreach ($service->formats as $format) {
//                if (!$this->findService($format, $service->actions)) {
//                    if ($this->ok) {
//                        $this->reason = 'Required service(s) not offered - ';
//                        $this->ok = false;
//                    } else {
//                        $this->reason .= ', ';
//                    }
//                    $this->reason .= "'{$format}' [" . implode(', ', $service->actions) . ']';
//                }
//            }
//        }
//    }
//
//
//// Validate message parameter constraints
//if ($this->ok) {
//    $invalidParameters = array();
//    foreach ($this->constraints as $name => $constraint) {
//        if (empty($constraint['messages']) || in_array($_POST['lti_message_type'], $constraint['messages'])) {
//            $ok = true;
//            if ($constraint['required']) {
//                if (!isset($_POST[$name]) || (strlen(trim($_POST[$name])) <= 0)) {
//                    $invalidParameters[] = "{$name} (missing)";
//                    $ok = false;
//                }
//            }
//            if ($ok && !is_null($constraint['max_length']) && isset($_POST[$name])) {
//                if (strlen(trim($_POST[$name])) > $constraint['max_length']) {
//                    $invalidParameters[] = "{$name} (too long)";
//                }
//            }
//        }
//    }
//    if (count($invalidParameters) > 0) {
//        $this->ok = false;
//        if (empty($this->reason)) {
//            $this->reason = 'Invalid parameter(s): ' . implode(', ', $invalidParameters) . '.';
//        }
//    }
//}
public function setRequestContext()
{
// Set the request context
    if (isset($_POST['context_id'])) {
        $this->context = Context::fromConsumer($this->consumer, trim($_POST['context_id']));
        $title = '';
        if (isset($_POST['context_title'])) {
            $title = trim($_POST['context_title']);
        }
        if (empty($title)) {
            $title = "Course {$this->context->getId()}";
        }
        $this->context->title = $title;
    }
}

public function setResourceLink(){
// Set the request resource link
    if (isset($_POST['resource_link_id'])) {
        $contentItemId = '';
        if (isset($_POST['custom_content_item_id'])) {
            $contentItemId = $_POST['custom_content_item_id'];
        }
        $this->resourceLink = ResourceLink::fromConsumer($this->consumer, trim($_POST['resource_link_id']), $contentItemId);
        $title = '';
        if (isset($_POST['resource_link_title'])) {
            $title = trim($_POST['resource_link_title']);
        }
        if (empty($title)) {
            $title = "Resource {$this->resourceLink->getId()}";
        }
        $this->resourceLink->title = $title;
// Delete any existing custom parameters
        foreach ($this->consumer->getSettings() as $name => $value) {
            if (strpos($name, 'custom_') === 0) {
                $this->consumer->setSetting($name);
                $doSaveConsumer = true;
            }
        }
        if (!empty($this->context)) {
            foreach ($this->context->getSettings() as $name => $value) {
                if (strpos($name, 'custom_') === 0) {
                    $this->context->setSetting($name);
                }
            }
        }
        foreach ($this->resourceLink->getSettings() as $name => $value) {
            if (strpos($name, 'custom_') === 0) {
                $this->resourceLink->setSetting($name);
            }
        }

// Save LTI parameters
        foreach (self::$LTI_CONSUMER_SETTING_NAMES as $name) {
            if (isset($_POST[$name])) {
                $this->consumer->setSetting($name, $_POST[$name]);
            } else {
                $this->consumer->setSetting($name);
            }
        }
        if (!empty($this->context)) {
            foreach (self::$LTI_CONTEXT_SETTING_NAMES as $name) {
                if (isset($_POST[$name])) {
                    $this->context->setSetting($name, $_POST[$name]);
                } else {
                    $this->context->setSetting($name);
                }
            }
        }
        foreach (self::$LTI_RESOURCE_LINK_SETTING_NAMES as $name) {
            if (isset($_POST[$name])) {
                $this->resourceLink->setSetting($name, $_POST[$name]);
            } else {
                $this->resourceLink->setSetting($name);
            }
        }
// Save other custom parameters
        foreach ($_POST as $name => $value) {
            if ((strpos($name, 'custom_') === 0) &&
                !in_array($name, array_merge(self::$LTI_CONSUMER_SETTING_NAMES, self::$LTI_CONTEXT_SETTING_NAMES, self::$LTI_RESOURCE_LINK_SETTING_NAMES))) {
                $this->resourceLink->setSetting($name, $value);
            }
        }
    }

// Set the user instance
    $userId = '';
    if (isset($_POST['user_id'])) {
        $userId = trim($_POST['user_id']);
    }

    $this->user = User::fromResourceLink($this->resourceLink, $userId);

// Set the user name
    $firstname = (isset($_POST['lis_person_name_given'])) ? $_POST['lis_person_name_given'] : '';
    $lastname = (isset($_POST['lis_person_name_family'])) ? $_POST['lis_person_name_family'] : '';
    $fullname = (isset($_POST['lis_person_name_full'])) ? $_POST['lis_person_name_full'] : '';
    $this->user->setNames($firstname, $lastname, $fullname);

// Set the user email
    $email = (isset($_POST['lis_person_contact_email_primary'])) ? $_POST['lis_person_contact_email_primary'] : '';
    $this->user->setEmail($email, $this->defaultEmail);




// Persist changes to consumer
if ($doSaveConsumer) {
    $this->consumer->save();
}
if ($this->ok && isset($this->context)) {
    $this->context->save();
}
if ($this->ok && isset($this->resourceLink)) {
    if (!empty($this->context)) {
        $this->resourceLink->setContextId($this->context->getRecordId());
    }

// Check if a share arrangement is in place for this resource link
    $this->ok = $this->checkForShare();

// Persist changes to resource link
    $this->resourceLink->save();

// Save the user instance
    $this->user->setResourceLinkId($this->resourceLink->getRecordId());
    if (isset($_POST['lis_result_sourcedid'])) {
        if ($this->user->ltiResultSourcedId !== $_POST['lis_result_sourcedid']) {
            $this->user->ltiResultSourcedId = $_POST['lis_result_sourcedid'];
            $this->user->save();
        }
    } else if (!empty($this->user->ltiResultSourcedId)) {
        $this->user->ltiResultSourcedId = '';
        $this->user->save();
    }
}

}

}
