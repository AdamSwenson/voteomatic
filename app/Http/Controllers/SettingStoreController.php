<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\Meeting;
use App\Models\SettingStore;
use Illuminate\Http\Request;

class SettingStoreController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth');

    }


    /**
     * Returns a blank settings object .
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingsRequest $request)
    {
        $this->setLoggedInUser();
        $meeting = Meeting::find($request->meetingId);
        $this->authorize('create', [SettingStore::class, $meeting]);

        $settings = SettingStore::create($request->except('meetingId'));
        $settings->user()->associate($this->user);
        $settings->meeting()->associate($meeting);

        $settings->save();

        return response()->json($settings);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SettingStore  $settings
     * @return \Illuminate\Http\Response
     */
    public function show(SettingStore $settings)
    {
$this->setLoggedInUser();
$this->authorize('view', $settings);
        return response()->json($settings);

    }

    /**
     * Update the specified resource in storage.
     *
     * NB, the SettingsRequest will check if the user is
     * authorized to access the settings being sent
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SettingStore  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(SettingsRequest $request, SettingStore $settings)
    {
        $this->setLoggedInUser();
        $this->authorize('update',  [SettingStore::class, $settings]);

        $settings->update($request->all());

        $settings->refresh();

        return response()->json($settings);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SettingStore  $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(SettingStore $settings)
    {
        //
    }
}
