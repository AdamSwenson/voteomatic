module.exports = {

    asyncComputed: {
        /**
         * In most cases will return this.motion.content.
         *
         * However, it will be overridden on the setup page by amendmentTextForSetup
         * (since the amendment object hasn't been created yet and the changes are stored locally
         * on the component)
         * @returns {string|*}
         */
        amendmentText: function () {
            //Overrides on the setup page
            if (!_.isUndefined(this.amendmentTextForSetup) && !_.isNull(this.amendmentTextForSetup)) {
                return this.amendmentTextForSetup;
            }

            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.content;
            }

        },


        isAmendment: function () {
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.isAmendment();
            }
        },

        /**
         * Whether this is an amendment to an amendment.
         * todo Make sure this doesn't also catch procedural motions like tabling
         */
        isSecondOrder: function () {
            if (!this.isAmendment) return false

            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.originalMotion.isAmendment();
//                return this.$store.getters.getMotionById(this.motion.applies_to);
            }

        },

        originalMotion: function () {
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.$store.getters.getMotionById(this.motion.applies_to);
            }
        },

        /**
         * In most cases will return the content stored on the motion object this
         * amendment applies to.
         * However, it will be overridden on the setup page by originalTextForSetup
         * @returns {string|*}
         */
        originalText: function () {
            //Overrides on the setup page (since the amendment object hasn't been created yet)
            if (!_.isUndefined(this.originalTextForSetup) && !_.isNull(this.originalTextForSetup)) {
                return this.originalTextForSetup;
            }

            try {
                let orig = this.$store.getters.getMotionById(this.motion.applies_to);
                return orig.content;
            } catch (e) {
                return '';
            }
        },


    }
}
