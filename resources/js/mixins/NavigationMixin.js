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
                    if(this.$router.currentRoute.name !== 'home') {
                        this.$router.push('meeting-home');
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

        forceNavigationToResultsTab : function (){
            // this.$store.commit()
        },

        forceNavigationToVoteTab : function (){},

    }
};
