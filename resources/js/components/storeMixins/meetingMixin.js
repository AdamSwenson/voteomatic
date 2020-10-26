/**
 * For any component that needs access to the
 * current meeting.
 *
 * @type {{computed: {}}}
 */
module.exports = {

    computed: {

        /**
         * The current global meeting
         */
        meeting: {
            get : function(){
                return this.$state.getters.getMeeting;
            },
            set : function(v){
                this.$state.commit('setMeeting', v);

            }
        }
    }
};
