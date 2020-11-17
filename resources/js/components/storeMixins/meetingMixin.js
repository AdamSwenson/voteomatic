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
                return this.$store.getters.getActiveMeeting;
            },
            set : function(v){
                this.$store.commit('setMeeting', v);

            }
        },


        meetingName: function () {
            if (_.isUndefined(this.meeting) || _.isNull(this.meeting)) return '';

            return this.meeting.name;
        },

        meetingDate: function () {
            if (_.isUndefined(this.meeting) || _.isNull(this.meeting)) return '';

            return this.meeting.readableDate();
        },
    }
};
