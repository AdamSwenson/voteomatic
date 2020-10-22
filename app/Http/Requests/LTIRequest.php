<?php


namespace App\Http\Requests;

use App\LTI\Authenticators\OAuth\OAuthUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LTIRequest extends FormRequest
{
    const BAD_VERSION_MSG = 'Invalid or missing lti_version parameter.';
    const BAD_TYPE_MSG = 'Invalid or missing lti_message_type parameter.';
    const BAD_CONSUMER_KEY = 'Missing consumer key.';
    /**
     * LTI version 1 for messages.
     */
    const LTI_VERSION1 = 'LTI-1p0';

    /**
     * LTI version 2 for messages.
     */
    const LTI_VERSION2 = 'LTI-2p0';

    /**
     * Permitted LTI versions for messages.
     */
    public static $LTI_VERSIONS = array(self::LTI_VERSION1, self::LTI_VERSION2);

    /**
     * List of supported message types.
     */
    public static $MESSAGE_TYPES = ['basic-lti-launch-request',
//        'ContentItemSelectionRequest',
//        'ToolProxyRegistrationRequest'
    ];


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //NB, these are all what is required for launching; other
        //actions will need to be checked against ToolPRoviderBase before using if need them
        return [
//            'lti_message_type' => ['required', Rule::in(self::$MESSAGE_TYPES)],
//            'lti_version' => ['required', Rule::in(self::$LTI_VERSIONS)],
//
//            'oauth_consumer_key' => ['required'],
//            'oath_nonce' => ['required'],
//            'oath_signature' => ['required'],
//            'oath_signature_method' => ['required'],
//            'oath_timestamp' => ['required'],
//            'oath_version'  => ['required'],
//
//            'resource_link_id' => ['required'],
//
//            'user_id' => ['required']
//            //
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'lti_message_type.required' => self::BAD_TYPE_MSG,
            'lti_message_type.in' => self::BAD_TYPE_MSG,
            'lti_version.required' => self::BAD_VERSION_MSG,
            'lti_version.in' => self::BAD_VERSION_MSG,
            'oauth_consumer_key.required' => self::BAD_CONSUMER_KEY,

        ];
    }


    //Things required by oauth

    /**
     * The request parameters, sorted and concatenated into a normalized string.
     * @return string
     */
    public function get_signable_parameters()
    {

        $params = $this->all();

        // Grab all parameters
//        $params = $this->parameters;

        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        if (isset($params['oauth_signature'])) {
            unset($params['oauth_signature']);
        }

        return OAuthUtil::build_http_query($params);

    }

    /**
     * Returns the base string of this request
     *
     * The base string defined as the method, the url
     * and the parameters (normalized), each urlencoded
     * and the concated with &.
     */
    public function get_signature_base_string()
    {
        $parts = array(
            //NB, this will return the method normalized to uppercase as required
            //by the signature
            $this->getMethod(),
            $this->get_normalized_http_url(),
            $this->get_signable_parameters()
        );

        $parts = OAuthUtil::urlencode_rfc3986($parts);

        return implode('&', $parts);

    }

    /**
     * parses the url and rebuilds it to be
     * scheme://host/path
     */
    public function get_normalized_http_url()
    {

//        $parts = parse_url($this->http_url);
        $parts = parse_url($this->url());

        $scheme = (isset($parts['scheme'])) ? $parts['scheme'] : 'http';
        $port = (isset($parts['port'])) ? $parts['port'] : (($scheme == 'https') ? '443' : '80');
        $host = (isset($parts['host'])) ? strtolower($parts['host']) : '';
        $path = (isset($parts['path'])) ? $parts['path'] : '';

        if (($scheme == 'https' && $port != '443')
            || ($scheme == 'http' && $port != '80')) {
            $host = "$host:$port";
        }

        return "$scheme://$host$path";

    }


}
