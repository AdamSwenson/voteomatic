/**
 * Handles all forced navigation for elections
 *
 * @type {{computed: {}}}
 */
const {isReadyToRock} = require("../../../utilities/readiness.utilities");
module.exports = {

    methods: {

        /**
         * Makes the decision on where to go and sends there
         */
        navigateToAppropriateLocation: function () {

            if (!isReadyToRock(this.meeting)) return null;

            //Election access has not yet been enabled by
            //the chair
            if (!this.meeting.isVotingAvailable && !this.meeting.isComplete) this.$store.commit('showPrematureCard')

            //The election has ended
            if (!this.meeting.isVotingAvailable && this.meeting.isComplete) {
                //Results are available
                //dev todo

                //No results available
                commit('showVotingCompleteCard');
            }

            //Voter has completed voting (The election could still be open for others)

            commit('showVotingCompleteCard');

            //Voter can vote for stuff
            dispatch('forceNavigationToElectionHome');

        },

        /**
         * Pushes the election home tab to the router.
         *
         * Unlike the meeting navigation, this does not rely on the watchers
         * that are defined in NavigationMixin.
         *
         * dev If we need websocket action, add the watcher to NavigationMixin
         *
         * @param dispatch
         * @param commit
         * @param getters
         */
        forceNavigationToElectionHome: function () {
            let me = this;
            // return new Promise(((resolve, reject) => {
                me.$router.push('election-home');
                // resolve();
            // }));
        },
    }
};
