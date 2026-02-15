<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteValidationRequest;
use App\Models\Vote;
use Illuminate\Http\Request;

class ReceiptValidationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Return a vote object if the receipt is in the database.
     * Return an error if model not found
     * @param VoteValidationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateReceipt(VoteValidationRequest $request)
    {

        $vote = Vote::where('receipt', $request->receipt)->firstOrFail();

        return response()->json($vote);

    }
}
