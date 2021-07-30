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
        motionResult : {
            get: function () {
                if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                    return this.$store.getters.getMotionResultObject(this.motion);
                }
            },
            default: null
        },

        /**
         * Whether the motion passed.
         * NB, this is set by the server separately from vote counts
         */
        isPassed: {
            get: function () {
                if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                    return this.$store.getters.getMotionPassed(this.motion);
                }
            },
            default: null
        },

        totalVotes: {

            get: function () {
                if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                    return this.$store.getters.getMotionTotalVoteCount(this.motion);
                }
            },
            default: null
        },
        //
        // /**
        //  * Not actually used by a component.
        //  * This watches the navTrigger value. When
        //  * an incoming websocket message tells us that the vote is complete,
        //  * this switches to the results tab. It then resets the navTrigger value
        //  * so that the user can navigate away from results.
        //  */
        // navTrigger: {
        //   get : function(){
        //     if(this.$store.getters.getNavTrigger === true){
        //         this.$router.push('results');
        //         this.$store.commit('setNavTrigger', false);
        //     }
        //   },
        //   // watch :
        // },

        nayCount: {

            get: function () {
                if (!_.isUndefined(this.motionResult) && !_.isNull(this.motionResult)) {
                    return this.$store.getters.getMotionNayCount( this.motion);
                }
            },
            default: '',
            watch: ['motionResult']
        },

        yayCount: {

            get: function () {
                if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                    return this.$store.getters.getMotionYayCount( this.motion);
                }
            },
            default: null
        }


    }
};
