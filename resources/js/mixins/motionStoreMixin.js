/**
 * For any component that needs access to the
 * current motion.
 *
 * For components that need access to common features of motion objects
 * (e.g., whether it has passed), see the motionObjectMixin
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
                this.$store.dispatch('setMotion', v);

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
                if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                    return this.motion.isComplete;
                }
            },
            default: false
        },

        /**
         * Whether voting has been closed.
         * @returns {{default: boolean, get: (function(): (module.exports.asyncComputed.isMotionComplete.motion.isComplete|undefined))}}
         */
        isComplete: function () {
            return this.isMotionComplete;
        },


        // selectedMotion: function () {
        //     return this.$store.getters.getActiveMotion;
        // },
        //
        // isAmendment: function(){
        //     if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
        //         return this.motion.isAmendment();
        //     }
        //
        // }

    }
};
