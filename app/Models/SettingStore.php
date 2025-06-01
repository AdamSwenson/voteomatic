<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingStore extends Model
{
    use HasFactory;

    const VALID_ELECTION_SETTINGS = [
        /** Whether candidates appear in random order or in stupid stupid stupid unfair alphabetical order */
        'randomize_candidates',

        /** Whether members have the option of directly making nominations for offices */
        'members_make_nominations',

        /** Whether to show vote counts in the results. If false, only shows winners  */
        'show_vote_counts',
    ];


    /**
     * These are setting names which can be
     * set by a user through a request
     *
     * NB, is_meeting_master does not appear here since it can
     * only be set via the repository
     */
    const VALID_MEETING_SETTINGS = [

        /** Whether events in the meeting (motions proposed, voting started, et cetera should be broadcast to users */
        'broadcast_events',

        /** Whether members have the option of directly making motions */
        'members_make_motions',

        /** Whether upon a motion being approved, a second is solicited */
        'members_second_motions',

//        /** Whether there is a publicly accessible view of the motion stack et cetera */
//        'public_view',

        /** Whether to show vote counts in the results. If false, only shows pass/fail */
        'show_vote_counts',

    ];

    /**
     * These are used when someone edits the settings from the client.
     * Since all settings must be part of VALID_SETTINGS, we will just get them
     * from here rather than store them in the database.
     * dev If settings start proliferating, we may want to start storing in the database
     */
    const SETTINGS_DISPLAY_PROPERTIES = [
        'show_vote_counts' => [
            'displayName' => "Show vote totals with results [Not enabled]",
            'displayDescription' => "Whether to show vote counts in the results. If false, only shows pass/fail.",
            'default' => true,
        ],

        'members_make_motions' => [
            'displayName' => "Members can make motions",
            'displayDescription' => "Whether members have the option of making motions directly",
            'default' => true,
        ],

        'members_second_motions' => [
            'displayName' => "Solicit second from members  [Not enabled]",
            'displayDescription' => "Whether the program should solicit a second once a member makes a motion.",
            'default' => true,
        ],

//        'public_view' => [
//            'displayName' => "Public view",
//            'displayDescription' => "Make the motion stack and amendment history available at a public link."
//        ],

        'broadcast_events' => [
            'displayName' => "Broadcast events  [Not enabled]",
            'displayDescription' => "Whether events such as motions, voting being opened or closed, et cetera should be pushed to all clients",
            'default' => true,
        ],

        //Election
        'members_make_nominations' => [
            'displayName' => "Members can make nominations  [Not enabled]",
            'displayDescription' => "Whether members have the option of making nominations directly",
            'default' => true,
        ],

        'randomize_candidates' => [
            'displayName' => "Randomize candidates",
            'displayDescription' => "Whether candidates appear in random order on the ballot",
            'default' => true,
        ],

    ];

    /**
     * Values which only the chair may set.
     * This will be checked by a policy.
     *
     * NB, is_meeting_master does not appear here since it can
     * only be set via the repository
     */
    const CHAIR_ONLY_SETTINGS = [
        //General
        'show_vote_counts',
        'broadcast_events',

        //Meeting specific
        'members_make_motions',
        'members_second_motions',

        //Election specific
        'members_make_nominations',
        'randomize_candidates'
    ];
//
//    /**
//     * Holds the key of the setting which the user
//     * wants to change. This is used during the
//     * policy checks and then by the setter.
//     * @var null
//     */
//    public $proposedSetting = null;
//
//    /**
//     * Holds the value that is being proposed
//     * to be set
//     * @var null
//     */
//    public $proposedValue = null;


    /**
     * NB, is_meeting_master does not appear here since it can
     * only be set via the repository
     *
     * @var string[]
     */
    protected $fillable = [
        'settings',

        'is_universal',

        'applies_to_all_members',

        //General
        'settings->show_vote_counts',

        'settings->broadcast_events',

        //Meeting specific
        'settings->members_make_motions',

        'settings->members_second_motions',


        //Election specific
        'settings->members_make_nominations',
        'settings->randomize_candidates',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_meeting_master' => 'boolean',
        'is_universal' => 'boolean',
        'applies_to_all_members' => 'boolean'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['display'];


    public function getDisplayAttribute()
    {
        return self::SETTINGS_DISPLAY_PROPERTIES;
    }


    /**
     * Returns VALID_ELECTION_SETTINGS or
     * VALID_MEETING_SETTINGS depending on what
     * sort of event this is.
     */
    public function getSettingsForEvent()
    {
        return $this->meeting->is_election ? self::VALID_ELECTION_SETTINGS : self::VALID_MEETING_SETTINGS;
    }

    /**
     * Updates the provided setting value and saves the model
     *
     * @param $settingName
     * @param $value
     */
    public function setSetting($settingName, $value)
    {

        $this->update(["settings->$settingName" => $value]);
        $this->save();
    }


    public function getSetting($settingName)
    {
        return $this->settings[$settingName];
    }

    public function getSettingNames()
    {
        return array_keys($this->settings);
    }

    /**
     * Allows to access $this->settingNames
     */
    public function getSettingNamesAttribute()
    {
        return $this->getSettingNames();
    }

    public function removeSetting($settingName)
    {
        return Arr::pull($this->settings, $settingName);
    }

    /**
     * The user whom these settings cover
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
