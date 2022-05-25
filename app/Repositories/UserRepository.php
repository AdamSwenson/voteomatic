<?php


namespace App\Repositories;


use App\Http\Requests\LTIRequest;
use App\Models\Meeting;
use App\Models\User;
use http\Env\Request;
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

        try {

            //try looking them up if we've seen their id before
            $user = User::where('user_id_hash', $userIdHash)->firstOrFail();

            //dev When we are ready to test on live server, this can be uncommented for VOT-179
//            $user = $this->updateEmail($user, $request);

        } catch (ModelNotFoundException $e) {
            //if they are new, we create them in the db
            $lastName = $request->lis_person_name_family;

            $firstName = $request->lis_person_name_given;


            $email = $this->getEmail($request);


            $user = User::create([
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'user_id_hash' => $userIdHash,
                'password' => Str::random(100),
//                'is_admin' => $admin
            ]);

            $user->save();
        }

        //New or old, we associate them with the meeting
        $user->meetings()->attach($meeting);

        return $user;
    }

    /**
     * Returns the email from the request or a constructed faux version
     * Added in VOT-179 to allow transition to using real email (requires that the LTI request send Public )
     * @param LTIRequest $request
     * @return string
     */
    public function getEmail(LTIRequest $request)
    {

        if ($request->has('lis_person_contact_email_primary')) return $request->lis_person_contact_email_primary;

        $lastName = $request->lis_person_name_family;

        $firstName = $request->lis_person_name_given;

        return "currently-unusable-" . $firstName . '.' . $lastName . '@csun.edu';
    }

    /**
     * This converts the unusable faux address from an existing user into the
     * actual email for VOT-179
     * @param User $user
     * @param LTIRequest $request
     * @return User
     */
    public function updateEmail(User $user, LTIRequest $request)
    {
        if (!$request->has('lis_person_contact_email_primary')) return $user;

        if ($request->lis_person_contact_email_primary === $user->email) return $user;

        $user->email = $request->lis_person_contact_email_primary;
        $user->save();
        return $user;

    }

}
