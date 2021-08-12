/**
 * For anything that needs access to the receipts for voting.
 *
 * Assumes something is defining this.motion
 *
 * @type {{vote: ((function(): (*|undefined))|*)}}
 */
module.exports = {

    asyncComputed: {
        /**
         * A vote object if one exists for the given motion
         * @returns {*}
         */
        vote: function () {
            if (!_.isNull(this.motion) && !_.isUndefined(this.motion)) return this.$store.getters.getCastVoteForMotion(this.motion);
        },

        receipt: function () {
            if (!_.isNull(this.vote) && !_.isUndefined(this.vote)) return this.vote.receipt;
            return '';
        }

    }
};
