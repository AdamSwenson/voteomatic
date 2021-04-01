<?php


namespace App\Http\Responses;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LogoutResponse
 *
 * dev Not yet working
 *
 * Based on https://stackoverflow.com/questions/65822861/laravel-fortify-logout-redirect
 *
 * @package App\Http\Responses
 */
class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect('/');
    }
}
