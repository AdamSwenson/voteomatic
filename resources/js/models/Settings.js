import IModel from "./IModel";

/**
 * Holds all settings loaded from server.
 *
 * NB, settings are aliased via dynamically created
 * getters and setters
 */
export default class Settings extends IModel {

    constructor({id = null, settings = null}) {
        super();
        this.id = id;
        this.settings = settings;

        let me = this;

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
}

