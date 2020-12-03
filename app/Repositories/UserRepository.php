<?php


namespace App\Repositories;


use App\Http\Requests\LTIRequest;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserRepository implements IUserRepository
{


    /**
     * Creates or looks up the user based on
     * the hashed user id sent in the LTI request.
     *
     * Also associates them with the meeting
     *
     *  //todo refactor this whole process to fit the laravel authentication patterns and utilities
     * @param LTIRequest $request
     * @param Meeting $meeting
     * @return User
     */
    public function getUserFromRequest(LTIRequest $request, Meeting $meeting)
    {
        $userIdHash = $request->user_id;

        //try looking them up if we've seen their id before
        try {
            $user = User::where('user_id_hash', $userIdHash)->firstOrFail();
        }catch(ModelNotFoundException $e){
            //if they are new, we create them in the db
            $lastName = $request->lis_person_name_family;

            $firstName = $request->lis_person_name_given;

            $email = "currently-unusable-" . $firstName . '.' . $lastName . '@csun.edu';

            $user = User::create([
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'user_id_hash' => $userIdHash,
                'password' => Str::random(100)
            ]);

            $user->save();
        }

        //New or old, we associate them with the meeting
        $user->meetings()->attach($meeting);

        return $user;
    }


}
