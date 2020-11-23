module.exports = {

    asyncComputed : {
        isAmendment: function () {
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.isAmendment();
            }
        },

        /**
         * Whether this is an amendment to an amendment.
         * todo Make sure this doesn't also catch procedural motions like tabling
         */
        isSecondOrder : function(){
            if(! this.isAmendment ) return false

            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.originalMotion.isAmendment();
//                return this.$store.getters.getMotionById(this.motion.applies_to);
            }

        },

        originalMotion : function(){
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.$store.getters.getMotionById(this.motion.applies_to);
            }
        },

        originalText: function () {
            try {
                let orig = this.$store.getters.getMotionById(this.motion.applies_to);
                return orig.content;
            } catch (e) {
                return '';
            }
        },



    }
}
