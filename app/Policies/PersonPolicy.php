<?php

namespace App\Policies;

    use App\Models\Person;
    use App\Models\User;
    use Illuminate\Auth\Access\HandlesAuthorization;

class PersonPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Determine whether the user can view all
     * persons associated with them.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewIndex(User $user)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Person $person
     * @return mixed
     */
    public function view(User $user, Person $person)
    {

        return true;

    }

    /**
     * Determine whether the user can create persons.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //regular users need to be able to do this
        //for write ins
        return true;

        //return $user->isChair();

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Person $person
     * @return mixed
     */
    public function update(User $user, Person $person)
    {
        return true;
        //return $user->isChair();

//        return $user->is($person->getOwner());

        //dd($user->is_admin);

        //Only administrators should be able to mess with persons
//        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Person $person
     * @return mixed
     */
    public function delete(User $user, Person $person)
    {
        return $user->isChair();

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param Person $person
     * @return mixed
     */
    public function restore(User $user, Person $person)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param Person $person
     * @return mixed
     */
    public function forceDelete(User $user, Person $person)
    {
        return $user->isChair();

    }
}


