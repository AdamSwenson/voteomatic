/**
 * Allows navigation to be handled by actions
 *
 * @type {{computed: {}}}
 */
module.exports = {

    computed: {
        // asyncComputed: {
        // /**
        //  * Not actually used by a component.
        //  * This watches the navTrigger value. When
        //  * an incoming websocket message tells us that the vote is complete,
        //  * this switches to the home tab. It then resets the navTrigger value
        //  * so that the user can navigate away from home.
        //  */
        // electionNomeNavTrigger: {
        //     get : function(){
        //         if(this.$store.getters.getElectionHomeNavTrigger === true){
        //             if(this.$router.currentRoute.name !== 'election-home') {
        //                 this.$router.push('election-home');
        //             }
        //             this.$store.commit('setHomeNavTrigger', false);
        //         }
        //     },
        //
        // },
        /**
         * This was updated in VOT-288 to directly use the router
         * rather than watching a value.
         * ===old
         * Not actually used by a component.
         * This watches the navTrigger value. When
         * an incoming websocket message tells us that the vote is complete,
         * this switches to the home tab. It then resets the navTrigger value
         * so that the user can navigate away from home.
         */
        homeNavTrigger: {
            get: function () {
                let me = this;
                if (this.$store.getters.getHomeNavTrigger === true) {
                    if (this.$store.getters.isElection) {
                        //dev Is this needed?
                        this.$router.push('election-home');
                    } else {
                        // if (this.$router.currentRoute.name !== 'home') {
                            this.$store.dispatch('forceNavigationToHome').then(() => {
                                me.$store.commit('setHomeNavTrigger', false);
                                window.console.log('NavigationMixin', 'get', 45,);
                            });
                            // this.$router.push('meeting-home');
                        }
                    // }
                    // this.$store.commit('setHomeNavTrigger', false);
                }
                return this.$store.getters.getHomeNavTrigger;
            },

        },

        /**
         * Not actually used by a component.
         * This watches the navTrigger value. When
         * an incoming websocket message tells us that the vote is complete,
         * this switches to the results tab. It then resets the navTrigger value
         * so that the user can navigate away from results.
         */
        resultsNavTrigger: {
            get: function () {
                if (this.$store.getters.getResultsNavTrigger === true) {
                    this.$store.dispatch('forceNavigationToResults');
                    // if (this.$router.currentRoute.name !== 'results') {
                    //
                    // }
                    this.$store.commit('setResultsNavTrigger', false);
                }

                return this.$store.getters.getResultsNavTrigger
            },


        },


        /**
         * Not actually used by a component.
         * This watches the navTrigger value. When
         * an incoming websocket message tells us that a motion needs to be voted upon
         * this switches to the vote tab. It then resets the navTrigger value
         * so that the user can navigate away.
         */
        voteNavTrigger: {
            get: function () {
                let me = this;
                if (this.$store.getters.getVoteNavTrigger === true) {
                    this.$store.dispatch('forceNavigationToVote').then(() => {
                        me.$store.commit('setVoteNavTrigger', false);
                    });
                    //
                    // if (this.$router.currentRoute.name !== 'vote') {
                    //     this.forceNavigationToVoteTab();
                    // }
                    // this.$store.commit('setVoteNavTrigger', false);
                }
                return this.$store.getters.getVoteNavTrigger;
            },

        },


    },

    methods: {
        //
        // /**
        //  * Opens the home tab.
        //  */
        // forceNavigationToHomeTab: function () {
        //     if (this.$store.getters.isElection) {
        //         this.$router.push('election-home');
        //     } else {
        //         window.console.log('NavigationMixin', 'forceNavigationToHomeTab', 114,);
        //         // if (this.$router.currentRoute.name !== 'home') {
        //             this.$router.push('meeting-home');
        //         // }
        //     }
        //
        // },
        //
        // /**
        //  * Opens the results tab
        //  */
        // forceNavigationToResultsTab: function () {
        //     this.$router.push('results');
        //
        // },
        //
        // /**
        //  * Opens the ballot tab.
        //  */
        // forceNavigationToVoteTab: function () {
        //     this.$router.push('vote');
        //
        // },

    }
};
