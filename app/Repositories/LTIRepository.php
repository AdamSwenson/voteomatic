<?php
//
//
namespace App\Repositories;


use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use Illuminate\Support\Str;

class LTIRepository
{

    const KEY_LENGTH = 32;

static public function generateConsumerKey(){
    return Str::random(self::KEY_LENGTH);
}


    static public function generateSecretKey(){
        return Str::random(self::KEY_LENGTH);
    }

    static public function generateResourceLinkId(){
        return Str::random(self::KEY_LENGTH);
    }

    /**
     * Creates the credentials and database entry for a new
     * canvas/other lti instance  which will connect to the voteomatic
     *
     * @param $name
     */
    public function createLTIConsumer($name){
        return LTIConsumer::create([
            'name' => $name,
            'consumer_key' => self::generateConsumerKey(),
            'secret_key' => self::generateSecretKey(),

        ]);


    }

    /**
     * @param LTIConsumer $consumer
     * @param Meeting $meeting
     * @param null $description
     * @return
     */
    public function createResourceLink(LTIConsumer $consumer, Meeting $meeting, $description=null){

        return ResourceLink::create([
            'lti_consumer_id' => $consumer->id,
            'meeting_id' => $meeting->id,
            'description' => $description,
            'resource_link_id' => self::generateResourceLinkId()
        ]);

    }
}









//
//use App\LTI\Authenticators\OAuth\OAuthUtil;
//use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;
//use IMSGlobal\LTI\ToolProvider\MediaType\ToolProfile;
//use IMSGlobal\LTI\ToolProvider\Service;
//use IMSGlobal\LTI\HTTPMessage;
//use IMSGlobal\LTI\OAuth;
//
//
///**
// * Class LTIRepository
// *
// * My attempt to replace all the library bloat.
// *
// * Based on
// *  *
// * @author  Stephen P Vickers <svickers@imsglobal.org>
// * @copyright  IMS Global Learning Consortium Inc
// * @date  2016
// * @version 3.0.2
// * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
// */
//class LTIRepository
//{
//
//    /**
//     * Local name of tool consumer.
//     *
//     * @var string $name
//     */
//    public $name = null;
//    /**
//     * Shared secret.
//     *
//     * @var string $secret
//     */
//    public $secret = null;
//    /**
//     * LTI version (as reported by last tool consumer connection).
//     *
//     * @var string $ltiVersion
//     */
//    public $ltiVersion = null;
//    /**
//     * Name of tool consumer (as reported by last tool consumer connection).
//     *
//     * @var string $consumerName
//     */
//    public $consumerName = null;
//    /**
//     * Tool consumer version (as reported by last tool consumer connection).
//     *
//     * @var string $consumerVersion
//     */
//    public $consumerVersion = null;
//    /**
//     * Tool consumer GUID (as reported by first tool consumer connection).
//     *
//     * @var string $consumerGuid
//     */
//    public $consumerGuid = null;
//    /**
//     * Optional CSS path (as reported by last tool consumer connection).
//     *
//     * @var string $cssPath
//     */
//    public $cssPath = null;
//    /**
//     * Whether the tool consumer instance is protected by matching the consumer_guid value in incoming requests.
//     *
//     * @var boolean $protected
//     */
//    public $protected = false;
//    /**
//     * Whether the tool consumer instance is enabled to accept incoming connection requests.
//     *
//     * @var boolean $enabled
//     */
//    public $enabled = false;
//    /**
//     * Date/time from which the the tool consumer instance is enabled to accept incoming connection requests.
//     *
//     * @var int $enableFrom
//     */
//    public $enableFrom = null;
//    /**
//     * Date/time until which the tool consumer instance is enabled to accept incoming connection requests.
//     *
//     * @var int $enableUntil
//     */
//    public $enableUntil = null;
//    /**
//     * Date of last connection from this tool consumer.
//     *
//     * @var int $lastAccess
//     */
//    public $lastAccess = null;
//    /**
//     * Default scope to use when generating an Id value for a user.
//     *
//     * @var int $idScope
//     */
//    public $idScope = ToolProvider::ID_SCOPE_ID_ONLY;
//    /**
//     * Default email address (or email domain) to use when no email address is provided for a user.
//     *
//     * @var string $defaultEmail
//     */
//    public $defaultEmail = '';
//    /**
//     * Setting values (LTI parameters, custom parameters and local parameters).
//     *
//     * @var array $settings
//     */
//    public $settings = null;
//    /**
//     * Date/time when the object was created.
//     *
//     * @var int $created
//     */
//    public $created = null;
//    /**
//     * Date/time when the object was last updated.
//     *
//     * @var int $updated
//     */
//    public $updated = null;
//    /**
//     * The consumer profile data.
//     *
//     * @var ToolProfile|null
//     */
//    public $profile = null;
//    /**
//     * @var string|null Json encoded Tool Proxy data.
//     */
//    public $toolProxy = null;
//
//    /**
//     * Consumer ID value.
//     *
//     * @var int|null $id
//     */
//    private $id = null;
//    /**
//     * Consumer key value.
//     *
//     * @var string $key
//     */
//    private $key = null;
//    /**
//     * Whether the settings value have changed since last saved.
//     *
//     * @var boolean $settingsChanged
//     */
//    private $settingsChanged = false;
//    /**
//     * Data connector object or string.
//     *
//     * @var DataConnector|null $dataConnector
//     */
//    private $dataConnector = null;
//
//    /**
//     * Class constructor.
//     *
//     */
//    public function __construct()
//    {
//
//        $this->id = null;
//        $this->key = null;
//        $this->name = null;
//        $this->secret = null;
//        $this->ltiVersion = null;
//        $this->consumerName = null;
//        $this->consumerVersion = null;
//        $this->consumerGuid = null;
//        $this->profile = null;
//        $this->toolProxy = null;
//        $this->settings = array();
//        $this->protected = false;
//        $this->enabled = false;
//        $this->enableFrom = null;
//        $this->enableUntil = null;
//        $this->lastAccess = null;
//        $this->idScope = ToolProvider::ID_SCOPE_ID_ONLY;
//        $this->defaultEmail = '';
//        $this->created = null;
//        $this->updated = null;
//
//
//        $this->secret = null; //32char
//
//    }
//
//
//    /**
//     * Is the consumer key available to accept launch requests?
//     *
//     * @return boolean True if the consumer key is enabled and within any date constraints
//     */
//    public function getIsAvailable()
//    {
//
//        $ok = $this->enabled;
//
//        $now = time();
//        if ($ok && !is_null($this->enableFrom)) {
//            $ok = $this->enableFrom <= $now;
//        }
//        if ($ok && !is_null($this->enableUntil)) {
//            $ok = $this->enableUntil > $now;
//        }
//
//        return $ok;
//
//    }
//
//    /**
//     * Check if the Tool Settings service is supported.
//     *
//     * @return boolean True if this tool consumer supports the Tool Settings service
//     */
//    public function hasToolSettingsService()
//    {
//
//        $url = $this->getSetting('custom_system_setting_url');
//
//        return !empty($url);
//
//    }
//
//    /**
//     * Get Tool Settings.
//     *
//     * @param boolean $simple True if all the simple media type is to be used (optional, default is true)
//     *
//     * @return mixed The array of settings if successful, otherwise false
//     */
//    public function getToolSettings($simple = true)
//    {
//
//        $url = $this->getSetting('custom_system_setting_url');
//        $service = new Service\ToolSettings($this, $url, $simple);
//        $response = $service->get();
//
//        return $response;
//
//    }
//
//    /**
//     * Perform a Tool Settings service request.
//     *
//     * @param array $settings An associative array of settings (optional, default is none)
//     *
//     * @return boolean True if action was successful, otherwise false
//     */
//    public function setToolSettings($settings = array())
//    {
//
//        $url = $this->getSetting('custom_system_setting_url');
//        $service = new Service\ToolSettings($this, $url);
//        $response = $service->set($settings);
//
//        return $response;
//
//    }
//
//    /**
//     * Add the OAuth signature to an LTI message.
//     *
//     * @param string $url URL for message request
//     * @param string $type LTI message type
//     * @param string $version LTI version
//     * @param array $params Message parameters
//     *
//     * @return array Array of signed message parameters
//     */
//    public function signParameters($url, $type, $version, $params)
//    {
//
//        if (!empty($url)) {
//// Check for query parameters which need to be included in the signature
//            $queryParams = array();
//            $queryString = parse_url($url, PHP_URL_QUERY);
//            if (!is_null($queryString)) {
//                $queryItems = explode('&', $queryString);
//                foreach ($queryItems as $item) {
//                    if (strpos($item, '=') !== false) {
//                        list($name, $value) = explode('=', $item);
//                        $queryParams[urldecode($name)] = urldecode($value);
//                    } else {
//                        $queryParams[urldecode($item)] = '';
//                    }
//                }
//            }
//            $params = $params + $queryParams;
//// Add standard parameters
//            $params['lti_version'] = $version;
//            $params['lti_message_type'] = $type;
//            $params['oauth_callback'] = 'about:blank';
//// Add OAuth signature
//            $hmacMethod = new OAuth\OAuthSignatureMethod_HMAC_SHA1();
//            $consumer = new OAuth\OAuthConsumer($this->getKey(), $this->secret, null);
//            $req = OAuth\OAuthRequest::from_consumer_and_token($consumer, null, 'POST', $url, $params);
//            $req->sign_request($hmacMethod, $consumer, null);
//            $params = $req->get_parameters();
//// Remove parameters being passed on the query string
//            foreach (array_keys($queryParams) as $name) {
//                unset($params[$name]);
//            }
//        }
//
//        return $params;
//
//    }
//
//    /**
//     * Add the OAuth signature to an array of message parameters or to a header string.
//     *
//     * @param string $endpoint
//     * @param string $consumerKey
//     * @param string $consumerSecret
//     * @param mixed $data
//     * @param string $method
//     * @param string|null $type
//     *
//     * @return mixed Array of signed message parameters or header string
//     * @throws OAuth\OAuthException
//     */
//    public static function addSignature($endpoint, $consumerKey, $consumerSecret, $data, $method = 'POST', $type = null)
//    {
//
//        $params = array();
//        if (is_array($data)) {
//            $params = $data;
//        }
//// Check for query parameters which need to be included in the signature
//        $queryParams = array();
//        $queryString = parse_url($endpoint, PHP_URL_QUERY);
//        if (!is_null($queryString)) {
//            $queryItems = explode('&', $queryString);
//            foreach ($queryItems as $item) {
//                if (strpos($item, '=') !== false) {
//                    list($name, $value) = explode('=', $item);
//                    $queryParams[urldecode($name)] = urldecode($value);
//                } else {
//                    $queryParams[urldecode($item)] = '';
//                }
//            }
//            $params = $params + $queryParams;
//        }
//
//        if (!is_array($data)) {
//// Calculate body hash
//            $hash = base64_encode(sha1($data, true));
//            $params['oauth_body_hash'] = $hash;
//        }
//
//// Add OAuth signature
//        $hmacMethod = new OAuth\OAuthSignatureMethod_HMAC_SHA1();
//        $oauthConsumer = new OAuth\OAuthConsumer($consumerKey, $consumerSecret, null);
//        $oauthReq = OAuth\OAuthRequest::from_consumer_and_token($oauthConsumer, null, $method, $endpoint, $params);
//        $oauthReq->sign_request($hmacMethod, $oauthConsumer, null);
//        $params = $oauthReq->get_parameters();
//// Remove parameters being passed on the query string
//        foreach (array_keys($queryParams) as $name) {
//            unset($params[$name]);
//        }
//
//        if (!is_array($data)) {
//            $header = $oauthReq->to_header();
//            if (empty($data)) {
//                if (!empty($type)) {
//                    $header .= "\nAccept: {$type}";
//                }
//            } else if (isset($type)) {
//                $header .= "\nContent-Type: {$type}";
//                $header .= "\nContent-Length: " . strlen($data);
//            }
//            return $header;
//        } else {
//            return $params;
//        }
//
//    }
//
//    /**
//     * Perform a service request
//     *
//     * @param object $service Service object to be executed
//     * @param string $method HTTP action
//     * @param string $format Media type
//     * @param mixed $data Array of parameters or body string
//     *
//     * @return HTTPMessage HTTP object containing request and response details
//     */
//    public function doServiceRequest($service, $method, $format, $data)
//    {
////
////        $header = self::addSignature($service->endpoint, $this->getKey(), $this->secret, $data, $method, $format);
////
////// Connect to tool consumer
////        $http = new HTTPMessage($service->endpoint, $method, $data, $header);
////// Parse JSON response
////        if ($http->send() && !empty($http->response)) {
////            $http->responseJson = json_decode($http->response);
////            $http->ok = !is_null($http->responseJson);
////        }
////
////        return $http;
//
//    }
//
//    public static function urlencode_rfc3986($input)
//    {
//
//        if (is_array($input)) {
//            return array_map(array('App\LTI\Authenticators\OAuth\OAuthUtil', 'urlencode_rfc3986'), $input);
//        } else if (is_scalar($input)) {
//            return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
//        } else {
//            return '';
//        }
//    }
//
//    // This decode function isn't taking into consideration the above
//    // modifications to the encoding process. However, this method doesn't
//    // seem to be used anywhere so leaving it as is.
//    public static function urldecode_rfc3986($string)
//    {
//        return urldecode($string);
//    }
//
//    // Utility function for turning the Authorization: header into
//    // parameters, has to do some unescaping
//    // Can filter out any non-oauth parameters if needed (default behaviour)
//    // May 28th, 2010 - method updated to tjerk.meesters for a speed improvement.
//    //                  see http://code.google.com/p/oauth/issues/detail?id=163
//    public static function split_header($header, $only_allow_oauth_parameters = true)
//    {
//
//        $params = array();
//        if (preg_match_all('/(' . ($only_allow_oauth_parameters ? 'oauth_' : '') . '[a-z_-]*)=(:?"([^"]*)"|([^,]*))/', $header, $matches)) {
//            foreach ($matches[1] as $i => $h) {
//                $params[$h] = OAuthUtil::urldecode_rfc3986(empty($matches[3][$i]) ? $matches[4][$i] : $matches[3][$i]);
//            }
//            if (isset($params['realm'])) {
//                unset($params['realm']);
//            }
//        }
//
//        return $params;
//
//    }
//
//    // helper to try to sort out headers for people who aren't running apache
//    public static function get_headers()
//    {
//
//        if (function_exists('apache_request_headers')) {
//            // we need this to get the actual Authorization: header
//            // because apache tends to tell us it doesn't exist
//            $headers = apache_request_headers();
//
//            // sanitize the output of apache_request_headers because
//            // we always want the keys to be Cased-Like-This and arh()
//            // returns the headers in the same case as they are in the
//            // request
//            $out = array();
//            foreach ($headers as $key => $value) {
//                $key = str_replace(" ", "-", ucwords(strtolower(str_replace("-", " ", $key))));
//                $out[$key] = $value;
//            }
//        } else {
//            // otherwise we don't have apache and are just going to have to hope
//            // that $_SERVER actually contains what we need
//            $out = array();
//            if (isset($_SERVER['CONTENT_TYPE']))
//                $out['Content-Type'] = $_SERVER['CONTENT_TYPE'];
//            if (isset($_ENV['CONTENT_TYPE']))
//                $out['Content-Type'] = $_ENV['CONTENT_TYPE'];
//
//            foreach ($_SERVER as $key => $value) {
//                if (substr($key, 0, 5) == 'HTTP_') {
//                    // this is chaos, basically it is just there to capitalize the first
//                    // letter of every word that is not an initial HTTP and strip HTTP
//                    // code from przemek
//                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
//                    $out[$key] = $value;
//                }
//            }
//        }
//        return $out;
//    }
//
//    // This function takes a input like a=b&a=c&d=e and returns the parsed
//    // parameters like this
//    // array('a' => array('b','c'), 'd' => 'e')
//    public static function parse_parameters($input)
//    {
//
//        if (!isset($input) || !$input) return array();
//
//        $pairs = explode('&', $input);
//
//        $parsed_parameters = array();
//        foreach ($pairs as $pair) {
//            $split = explode('=', $pair, 2);
//            $parameter = self::urldecode_rfc3986($split[0]);
//            $value = isset($split[1]) ? self::urldecode_rfc3986($split[1]) : '';
//
//            if (isset($parsed_parameters[$parameter])) {
//                // We have already recieved parameter(s) with this name, so add to the list
//                // of parameters with this name
//
//                if (is_scalar($parsed_parameters[$parameter])) {
//                    // This is the first duplicate, so transform scalar (string) into an array
//                    // so we can add the duplicates
//                    $parsed_parameters[$parameter] = array($parsed_parameters[$parameter]);
//                }
//
//                $parsed_parameters[$parameter][] = $value;
//            } else {
//                $parsed_parameters[$parameter] = $value;
//            }
//        }
//
//        return $parsed_parameters;
//
//    }
//
//    public static function build_http_query($params)
//    {
//
//        if (!$params) return '';
//
//        // Urlencode both keys and values
//        $keys = OAuthUtil::urlencode_rfc3986(array_keys($params));
//        $values = OAuthUtil::urlencode_rfc3986(array_values($params));
//        $params = array_combine($keys, $values);
//
//        // Parameters are sorted by name, using lexicographical byte value ordering.
//        // Ref: Spec: 9.1.1 (1)
//        uksort($params, 'strcmp');
//
//        $pairs = array();
//        foreach ($params as $parameter => $value) {
//            if (is_array($value)) {
//                // If two or more parameters share the same name, they are sorted by their value
//                // Ref: Spec: 9.1.1 (1)
//                // June 12th, 2010 - changed to sort because of issue 164 by hidetaka
//                sort($value, SORT_STRING);
//                foreach ($value as $duplicate_value) {
//                    $pairs[] = $parameter . '=' . $duplicate_value;
//                }
//            } else {
//                $pairs[] = $parameter . '=' . $value;
//            }
//        }
//
//        // For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
//        // Each name-value pair is separated by an '&' character (ASCII code 38)
//        return implode('&', $pairs);
//
//    }
//
//
//
//
//}
