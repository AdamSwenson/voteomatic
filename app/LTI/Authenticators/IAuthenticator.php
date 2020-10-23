<?php


namespace App\LTI\Authenticators;

use App\Http\Requests\LTIRequest;
use App\Models\ResourceLink;

/**
 * Interface IAuthenticator
 * Interface for everything returned by the authenticator factory
 * @package App\LTI\Authenticators
 */
interface IAuthenticator
{

    public function authenticate(LTIRequest $request, ResourceLink $resourceLink);


}
