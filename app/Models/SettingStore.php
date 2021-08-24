<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingStore extends Model
{
    use HasFactory;

    /**
     * These are setting names which can be
     * set by a user through a request
     *
     * NB, is_meeting_master does not appear here since it can
     * only be set via the repository
     */
    const VALID_SETTINGS = [
        'members_make_motions',

        'show_vote_counts',
    ];

    /**
     * Values which only the chair may set.
     * This will be checked by a policy.
     *
     * NB, is_meeting_master does not appear here since it can
     * only be set via the repository
     */
    const CHAIR_ONLY_SETTINGS = [
        'members_make_motions',

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

        'settings->members_make_motions',

        'settings->show_vote_counts',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_meeting_master' => 'boolean',
        'is_universal' => 'boolean',
        'applies_to_all_members' => 'boolean'
    ];




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
