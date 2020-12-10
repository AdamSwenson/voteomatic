<?php


namespace Tests\helpers;

use App\LTI\Authenticators\OAuth\OAuthSignatureMethod_HMAC_SHA1;
use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\User;
use App\Models\ResourceLink;
use IMSGlobal\LTI\ToolProvider\ToolConsumer;
//use Faker\Generator as Faker;
use Faker;
use Ramsey\Uuid\Type\Integer;

/**
 * Class LTIPayloadMaker
 * Creates dummy LTI payloads for testing
 *
 * @package Tests\helpers
 */
class LTIPayloadMaker
{

    const MESSAGE_TYPE = 'basic-lti-launch-request';
    const LTI_VERSION = 'LTI-1p0';
    const OAUTH_CALLBACK = 'about:blank';

    public static function makePayload(Meeting $meeting, $endpoint = 'https://www.test.com/test', $resourceLink=null, $user=null, $otherData = [])
    {
        /**
         * Notes
         * lti_message_type    required    set to basic-lti-launch-request
         * lti_version    required    set to LTI-1p0
         * resource_link_id    required    unique id referencing the link, or "placement", of the app in the consumer. If an app was added twice to the same class, each placement would send a different id, and should be considered a unique "launch". For example, if the provider were a chat room app, then each resource_link_id would be a separate room.
         * user_id    recommended    unique id referencing the user accessing the app. provider should consider this id an opaque identifier.
         * user_image    optional    if provided, this would be a url to an avatar image for the current user. We recommend that these urls be 50px wide and tall, and don't expire for at least 3 months.
         * roles    recommended
         *
         * there's a long list of possible roles, but here's the most common ones:
         *
         * Learner
         * Instructor
         * ContentDeveloper
         * urn:lti:instrole:ims/lis/Observer
         * urn:lti:instrole:ims/lis/Administrator
         *
         * lis_person_name_full    recommended    Full name of the user accessing the app. This won't be sent if apps are configured to launch users anonymously or with minimal information.
         * lis_outcome_service_url    optional    If this url is passed to the provider then it means the app is authorized to send grade values back to the consumer gradebook for any students that access the app. There's more information available in the Canvas API documentation.
         * selection_directive    optional    If this parameter is passed to the provider then it means the consumer is expecting the provider to return some piece of information such as a url, an html snippet, etc. There's more information available in the Canvas API documentation.
         */

        $faker = Faker\Factory::create();

        $j = $faker->sha1;

        //default test for now
        $consumerKey = 'tacokey';
        $consumerSecret = 'nom';

        if(is_null($resourceLink)){
            $resourceLink = ResourceLink::factory()->create();
            $consumer = LTIConsumer::factory()->create();
            $resourceLink->ltiConsumer()->associate($consumer)->save();
        }

        //if we just got the id as resource link
        $resourceLinkId = is_integer($resourceLink) ? $resourceLink : $resourceLink->id;

        $user = $user ? $user : User::factory()->create();

        //default data fields
        $data = [
            /* LTI */
            'lti_message_type' => self::MESSAGE_TYPE,
            'lti_version' => self::LTI_VERSION,

            /* The Canvas assignment which is linking to the meeting */
            'resource_link_id' => $resourceLinkId,
            'resource_link_title' => $resourceLink->description,

            /* The user's info */
            'user_id' => $user->user_id_hash,
            'lis_person_name_family' => $user->last_name,
            'lis_person_name_given' => $user->first_name,

            /* Oauth stuff */
            'oauth_callback' => self::OAUTH_CALLBACK,
            'oauth_consumer_key' => $resourceLink->ltiConsumer->consumer_key,

            //these will be added by the tool consumer below
//            'oath_nonce' => $faker->sha1,
//            'oath_signature' => ['required'],
//            'oath_signature_method' => ['required'],
//            'oath_timestamp' => $faker->dateTime(),
//            'oath_version'  => '1.0',
//
//            'custom_debug' => true,
        ];

//        $request = new LTIRequest();
        //add any additional data params
        foreach ($otherData as $k => $v) {
//            $request[$k] = $v;
            $data[$k] = $v;
        }

//        $signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();

//        $t = $signatureMethod->build_signature($request, $resourceLink);

//        public static function addSignature($endpoint, $consumerKey, $consumerSecret, $data, $method = 'POST', $type = null)
        $t = ToolConsumer::addSignature($endpoint, $resourceLink->ltiConsumer->consumerKey, $resourceLink->ltiConsumer->secret_key, $data);

        return $t;

    }



    static public function specifyPayloadContents($endpoint, $consumerKey, $consumerSecretKey, $resourceLinkId, $userId, $otherData){
        $faker = Faker\Factory::create();

        //default data fields
        $data = [
            'lti_message_type' => self::MESSAGE_TYPE,
            'lti_version' => self::LTI_VERSION,
            'oauth_callback' => self::OAUTH_CALLBACK,

            'oauth_consumer_key' => $consumerKey,
            'resource_link_id' => $resourceLinkId,

            'user_id' => $userId,
            'lis_person_name_family' => $faker->lastName,
            'lis_person_name_given' => $faker->firstName
        ];

        //add any additional data params
        foreach ($otherData as $k => $v) {
            $data[$k] = $v;
        }

        $t = ToolConsumer::addSignature($endpoint, $consumerKey, $consumerSecretKey, $data);

        return $t;

    }


}
