<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Http\Requests\Election\PersonRequest;
use App\Models\Election\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }
//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index()
//    {
//        //
//    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }
    /**
     * Remove the specified resource from storage.
     *
     * @param Person $person
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Person $person)
    {
        $this->setLoggedInUser();
        $this->authorize('delete', [Person::class, $person]);
        $person->delete();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param PersonRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonRequest $request)
    {
        $this->setLoggedInUser();
        $this->authorize('create',[Person::class]);

        $person = Person::create($request->all());
        return response()->json($person);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        $this->setLoggedInUser();
        return $this->authorize('view', [Person::class, $person]);
        return response()->json($person);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Person $person, PersonRequest $request)
    {
        $this->setLoggedInUser();
        $this->authorize('update', [Person::class, $person]);
        $person->update($request->all());
        return response()->json($person);
    }


}
