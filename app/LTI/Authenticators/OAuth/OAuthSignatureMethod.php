<?php

namespace App\LTI\Authenticators\OAuth;

use App\Http\Requests\LTIRequest;
use App\LTI\Exceptions\OAuthException;
use App\ResourceLink;

/**
 * Class to represent an %OAuth Signature Method
 *
 * @copyright  Andy Smith
 * @version 2008-08-04
 * @license https://opensource.org/licenses/MIT The MIT License
 */
/**
 * A class for implementing a Signature Method
 * See section 9 ("Signing Requests") in the spec
 */
abstract class OAuthSignatureMethod {
    /**
     * Needs to return the name of the Signature Method (ie HMAC-SHA1)
     * @return string
     */
    public function get_name(){
        return $this->name;
    }

    /**
     * Build up the signature
     * NOTE: The output of this function MUST NOT be urlencoded.
     * the encoding is handled in OAuthRequest when the final
     * request is serialized
     * @param LTIRequest $request
     * @param ResourceLink $resourceLink
     * @param OAuthToken $token
     * @return string
     */
    abstract public function build_signature(LTIRequest $request, ResourceLink $resourceLink, $token=null);

    /**
     * Verifies that a given signature is correct
     * @param OAuthRequest $request
     * @param OAuthConsumer $consumer
     * @param OAuthToken $token
     * @param string $signature
     * @return bool
     */
    public function check_signature(LTIRequest $request, ResourceLink $resourceLink,  $token=null) {
        $incoming_signature = $request->oauth_signature;

        $built = $this->build_signature($request, $resourceLink, $token);

        // Check for zero length, although unlikely here
        if (strlen($built) == 0 || strlen($incoming_signature) == 0) {
            throw new OAuthException('Invalid signature: zero length');
        }

        if (strlen($built) != strlen($incoming_signature)) {
            throw new OAuthException('Invalid signature: length mismatch');
        }

        // Avoid a timing leak with a (hopefully) time insensitive compare
        $result = 0;
        for ($i = 0; $i < strlen($incoming_signature); $i++) {
            $result |= ord($built[$i]) ^ ord($incoming_signature[$i]);

            if ($result !== 0){
                throw new OAuthException("Invalid signature: (built) $built vs (incoming) $incoming_signature");
            }

        }

        return true;
//        return $result == 0;

    }

}
