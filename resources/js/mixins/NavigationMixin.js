/**
 * Allows navigation to be handled by actions
 *
 * @type {{computed: {}}}
 */
module.exports = {


    asyncComputed: {
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
         * Not actually used by a component.
         * This watches the navTrigger value. When
         * an incoming websocket message tells us that the vote is complete,
         * this switches to the home tab. It then resets the navTrigger value
         * so that the user can navigate away from home.
         */
        homeNavTrigger: {
            get : function(){
                if(this.$store.getters.getHomeNavTrigger === true){
                    if(this.$store.getters.isElection){
                        this.$router.push('election-home');
                    }
                    else {
                        if (this.$router.currentRoute.name !== 'home') {
                            this.$router.push('meeting-home');
                        }
                    }
                    this.$store.commit('setHomeNavTrigger', false);
                }
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
          get : function(){
            if(this.$store.getters.getResultsNavTrigger === true){
                if(this.$router.currentRoute.name !== 'results'){
                    this.$router.push('results');
                }
                this.$store.commit('setResultsNavTrigger', false);
            }
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
            get : function(){
                if(this.$store.getters.getVoteNavTrigger === true){

                    if(this.$router.currentRoute.name !== 'vote') {
                        this.$router.push('vote');
                    }
                    this.$store.commit('setVoteNavTrigger', false);
                }
            },

        },


    },

    methods : {

    //     forceNavigationToResultsTab : function (){
    //         // this.$store.commit()
    //     },
    //
    //     forceNavigationToVoteTab : function (){},
    //
    }
};
