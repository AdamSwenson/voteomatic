/**
 * For any component that needs access to properties
 * of whatever motion is set as this.motion (regardless of whether
 * it is the currently active motion).
 *
 * For components that need access to the currently active motion
 * see the motionStoretMixin
 *
 * @type {{computed: {}}}
 */
module.exports = {



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

        isAmendment: function(){
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.isAmendment();
            }
        },

        // isDebatable: function(){
        //     if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
        //         return this.motion.isDebatable();
        //     }
        // },



    }
};
