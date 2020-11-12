<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;

class ReceiptValidationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }




    public function validateReceipt(Request $request)
    {
//        dd($request->receipt);
        $vote = Vote::where('receipt', $request->receipt)->firstOrFail();

        return response()->json($vote);

    }
}
