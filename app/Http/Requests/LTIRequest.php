<?php


namespace App\Http\Requests;

use App\LTI\Authenticators\OAuth\OAuthUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LTIRequest
 *
 * Request will look like:
 *   "oauth_consumer_key": "7a9501fa2e9acd4e887ca355012cad2b93f7ddf01b0cd89b299cd377",
 * "oauth_signature_method": "HMAC-SHA1",
 * "oauth_timestamp": "1603493439",
 * "oauth_nonce": "av22SIRLDUPGlkGOIv709KQ6iOfCNIWNOZPBwqtL0",
 * "oauth_version": "1.0",
 * "context_id": "522866e77b5756393414c4205c85ac295cd80216",
 * "context_label": "Adam+Swenson+Test+Course",
 * "context_title": "Adam+Swenson+Test+Course",
 * "custom_canvas_assignment_points_possible": "3",
 * "custom_canvas_assignment_title": "ltitest4",
 * "custom_canvas_enrollment_state": "active",
 * "ext_ims_lis_basic_outcome_url": "https://canvas.csun.edu/api/lti/v1/tools/10341/ext_grade_passback",
 * "ext_lti_assignment_id": "00205308-702a-4a86-a3ab-df7af8d28c45",
 * "ext_outcome_data_values_accepted": "url,text",
 * "ext_outcome_result_total_score_accepted": "true",
 * "ext_outcome_submission_submitted_at_accepted": "true",
 * "ext_outcomes_tool_placement_url": "https://canvas.csun.edu/api/lti/v1/turnitin/outcomes_placement/10341",
 * "ext_roles": "urn:lti:instrole:ims/lis/Instructor,urn:lti:instrole:ims/lis/Student,urn:lti:role:ims/lis/Instructor,urn:lti:role:ims/lis/Learner/NonCreditLearner,urn:lti:role:ims/lis/Mentor,urn:lti:sysrole:ims/lis/User",
 * "launch_presentation_document_target": "iframe",
 * "launch_presentation_locale": "en",
 * "launch_presentation_return_url": "https://canvas.csun.edu/courses/85210/assignments",
 * "lis_outcome_service_url": "https://canvas.csun.edu/api/lti/v1/tools/10341/grade_passback",
 * "lis_person_name_family": "Swenson",
 * "lis_person_name_full": "Adam+Swenson",
 * "lis_person_name_given": "Adam",
 * "lti_message_type": "basic-lti-launch-request",
 * "lti_version": "LTI-1p0",
 * "oauth_callback": "about:blank",
 * "resource_link_id": "4f7d7beaced17c12e252c18b003c5200176a81b0",
 * "resource_link_title": "ltitest4",
 * "roles": "Instructor",
 * "tool_consumer_info_product_family_code": "canvas",
 * "tool_consumer_info_version": "cloud",
 * "tool_consumer_instance_contact_email": "notifications@instructure.com",
 * "tool_consumer_instance_guid": "7db438071375c02373713c12c73869ff2f470b68.csun.instructure.com",
 * "tool_consumer_instance_name": "California+State+University+Northridge",
 * "user_id": "6d6d9761019fbcf67efbe2730d66ef66c92331a7",
 * "oauth_signature": "ZIozADuiLEGRkiLbHXrIQC7UEQ8="
 *
 * @package App\Http\Requests
 */
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
            /* Oauth stuff */
            'oauth_consumer_key' => ['required'],
            'oauth_nonce' => ['required'],
            'oauth_signature' => ['required'],
            'oauth_signature_method' => ['required'],
            'oauth_timestamp' => ['required'],
            'oauth_version'  => ['required'],

            /* LTI */
            'lti_message_type' => ['required', Rule::in(self::$MESSAGE_TYPES)],
            'lti_version' => ['required', Rule::in(self::$LTI_VERSIONS)],

            /* The Canvas assignment which is linking to the meeting */
            'resource_link_id' => ['required'],
            'resource_link_title' => ['required'],

            /* The user's info */
            'user_id' => ['required'],
             'lis_person_name_family' => ['required'],
            'lis_person_name_given' => ['required']

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
//            'lti_message_type.required' => self::BAD_TYPE_MSG,
//            'lti_message_type.in' => self::BAD_TYPE_MSG,
//            'lti_version.required' => self::BAD_VERSION_MSG,
//            'lti_version.in' => self::BAD_VERSION_MSG,
//            'oauth_consumer_key.required' => self::BAD_CONSUMER_KEY,

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
