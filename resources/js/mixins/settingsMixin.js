/**
 *
 */
const Payload = require("../models/Payload");
module.exports = {

    asyncComputed: {

        settings: function () {
            let s = this.$store.getters.getSettings;
            if (!_.isNull(s) && !_.isUndefined(s)) return s;
        }

    },

    computed: {},

    methods: {

        updateSettingValue: (settingName, newValue) => {
            return new Promise(((resolve, reject) => {

                let pl = Payload.factory({
                    'updateProp': settingName,
                    'updateVal': newValue,
                });
                return this.$store.dispatch('updateSettings', pl).then(() => {
                    return resolve();
                });

            }));
        }

    }

};
