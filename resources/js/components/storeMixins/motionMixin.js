/**
 * For any component that needs access to the
 * current motion.
 *
 * @type {{computed: {}}}
 */
module.exports = {

    computed: {
        /**
         * The current global motion
         */
        motion: {
            get: function () {
                return this.$store.getters.getActiveMotion;
            },

            set: function (v) {
                this.$store.commit('setMotion', v);

            }
        },
    },

    asyncComputed: {

        /**
         * If true, voting has ended on the motion.
         * If false, voting has either not begun or is in progress
         */
        isMotionComplete: {
            get: function () {
                // if (_.isUndefined(this.motion) || _.isNull(this.motion)) return false
                return this.motion.isComplete;
            },
            default: false
        },

        selectedMotion: function () {
            return this.$store.getters.getActiveMotion;
        },

    }
};
