<?php

namespace App\Http\Controllers\LTI;

use App\Repositories\ILTIRepository;
use Illuminate\Http\Request;

/**
 * Class LTICredentialsController
 *
 * Handles requests to set up a new app connection to
 * canvas.
 *
 * This would be used if, e.g., someone wanted to set up
 * the voteomatic for their own use
 *
 * @package App\Http\Controllers\LTI
 */
class LTICredentialsController extends Controller
{
    //

    /**
     * @var ILTIRepository
     */
    public $LTIRepository;

    public function __construct(ILTIRepository $LTIRepository)
    {
        $this->middleware('auth');
        $this->LTIRepository = $LTIRepository;
    }


    public function makeCredentials(Request $request){
        //todo Serious authorization logic goes here

        return $this->LTIRepository->createLTIConsumer($request->name);


    }



}
