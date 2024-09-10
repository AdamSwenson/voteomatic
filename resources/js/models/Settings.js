import IModel from "./IModel";

/**
 * Holds all settings loaded from server.
 *
 * NB, settings are aliased via dynamically created
 * getters and setters
 */
export default class Settings extends IModel {

    constructor({id = null, settings = null, display = null, meeting_id = null}) {
        super();
        this.id = id;
        this.settings = settings;
        this.display = display;
        this.meetingId = meeting_id;

        let me = this;

        // //Order the settings by the display name
        // let d = _.orderBy(this.display, 'displayName');

        _.forEach(this.settings, (v, k) => {

            /**
             * We alias all of the contents in _settings
             * via getters and setters
             */
            Object.defineProperty(me, k, {
                set: function (v) {
                    me.settings[k] = v;
                },
                get: function () {
                    return me.settings[k];
                }
            });

        });


    }

//     get orderedNames() {
//         //make an array
//         let o = [];
//         _.forEach(this.display, (d) => {
//             o.push(d);
//         });
// return        _.orderBy(o, ['displayName']);
//     }

    get settingNames() {

        // return _.keys(d);

        return _.keys(this.settings);
    }

    getDisplayForSetting(settingName) {
        return this.display[settingName];
    }

    /**
     * Returns true if the name is in the settings object
     * and has the value true
     *
     * @param settingName
     */
    isSettingTrue(settingName) {
        if (!_.has(this.settings, settingName)) return false;

        return this.settings[settingName] === true;
    }

    /**
     * Returns true if any of the list of names is
     * in the setting object and is true
     * @param listOfSettingNames
     */
    isAnySettingTrue(listOfSettingNames) {
        let me = this;
        let v = false;
        _.forEach(listOfSettingNames, (settingName) => {
            if (me.isSettingTrue(settingName)) v = true;
        });
        return v;
    }
}

