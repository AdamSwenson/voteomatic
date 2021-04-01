/**
 * For any component that needs access to the
 * current meeting.
 *
 * @type {{computed: {}}}
 */
module.exports = {
    data: function () {
        return {
            linkBase: "https://voteomatic.com/lti-entry/"
        }
    },

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


        /**
         * The link that the user will enter into
         * canvas
         */
        meetingLink: {

            get: function () {
                return this.linkBase + this.meeting.id;
            },
            default: false
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
