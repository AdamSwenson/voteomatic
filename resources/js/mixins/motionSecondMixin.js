/**
 * For any component that needs access to the results of
 * a voted upon motion.
 *
 * Assumes that the component has a motion set at this.motion
 *
 * @type {{computed: {}}}
 */
module.exports = {


    asyncComputed: {

        motionPendingSecond: {
            get: function () {
                return this.$store.getters.getMotionPendingSecond;
            },
            watch : []
        },

        /**
         * Returns true if a motion is awaiting someone to second it
         * @returns {boolean}
         */
        isMotionPendingSecond: function () {
            return !_.isUndefined(this.motionPendingSecond) && !_.isNull(this.motionPendingSecond);
        }

    },

    // methods : {
    //     secondMotion : function(){
    //
    //     }
    // }
};
