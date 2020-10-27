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
                return this.$store.getters.getMeeting;
            },
            set : function(v){
                this.$store.commit('setMeeting', v);

            }
        }
    }
};
