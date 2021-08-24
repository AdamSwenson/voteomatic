<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\Meeting;
use App\Models\SettingStore;
use App\Repositories\ISettingsRepository;
use Illuminate\Http\Request;

class SettingStoreController extends Controller
{
    public $settingsRepo;

    public function __construct()
    {

        $this->middleware('auth');
        $this->settingsRepo = app()->make(ISettingsRepository::class);
    }


    /**
     * This will have had the meetingId as a parameter,
     * so it returns the user's settings for the meeting (overwritten
     * with any master settings).
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserSettings(Meeting $meeting)
    {
        $this->setLoggedInUser();
//        $meeting = Meeting::find($request->meetingId);

        //prevents someone not part of the meeting from having settings created for them
        $this->authorize('view', [SettingStore::class, $meeting]);


        $settings = $this->settingsRepo->getConsolidatedSettings($meeting, $this->user);

        return response()->json($settings);
    }

    public function getMasterSettings(Meeting $meeting)
    {
        $this->setLoggedInUser();
        $this->authorize('viewMaster', SettingStore::class);

        $settings = $meeting->getMasterSettingStore();

        return response()->json($settings);
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
     * @param \Illuminate\Http\Request $request
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
     * @param \App\Models\SettingStore $settings
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\SettingStore $settings
     * @return \Illuminate\Http\Response
     */
    public function update(SettingsRequest $request, SettingStore $settings)
    {
        $this->setLoggedInUser();
        $this->authorize('update', [SettingStore::class, $settings]);

        $settings->update($request->all());

        $settings->refresh();

        return response()->json($settings);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\SettingStore $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(SettingStore $settings)
    {
        //
    }
}
