/**
 *
 */
module.exports = {



    methods: {
        /**
         * Sets the motion as current on client but not
         * on server. Used by regular user to select past
         * motions and view results
         *
         * Requires that this.motion be set
         */
        setMotionLocal : function() {
            this.$store.commit('setMotion', this.motion);
        },


    }

};
