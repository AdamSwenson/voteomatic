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


        isAmendment: function(){
            if (!_.isUndefined(this.motion) && ! _.isNull(this.motion)) {
                return this.motion.isAmendment();
            }
        },

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

        isMotionReady: function () {
            return ! _.isUndefined(this.motion) && ! _.isNull(this.motion);
        },


        /**
         * Whether the motion is a html formatted resolution
         * which will require special display options
         * @returns {boolean|boolean|*}
         */
        isResolution: function(){
            if(_.isUndefined(this.motion) || _.isNull(this.motion)) return false;

            return this.motion.isResolution;
        },

        /**
         * Whether another motion has superseded this one
         * (e.g., if it was altered by an amendment)
         * @returns {*}
         */
        isSuperseded: function(){
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.isSuperseded();
            }

        }

        // isDebatable: function(){
        //     if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
        //         return this.motion.isDebatable();
        //     }
        // },



    }
};
