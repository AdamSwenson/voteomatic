/**
 * For any component that needs access to the
 * current meeting.
 *
 * @type {{computed: {}}}
 */
// import {isReadyToRock} from "../utilities/readiness.utilities";

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

        /**
         *  The string used on buttons etc.
         *  Usually either 'meeting' or 'election'
         * @returns {string|*}
         */
        type : function(){
            if (_.isUndefined(this.meeting) || _.isNull(this.meeting)) return '';
            return this.meeting.type
        },

        /**
         * First letter capitalized for use in labels etc
         * @returns {string}
         */
        typeCapitalized : function(){
            if (_.isUndefined(this.meeting) || _.isNull(this.meeting)) return '';
            return _.capitalize(this.type);
        },

        /**
         * What the basic things we operate on are called.
         * Again used for buttons etc
         * @returns {string|*|string}
         */
        subsidiaryType: function () {
            if (_.isUndefined(this.meeting) || _.isNull(this.meeting)) return '';
            return this.meeting.subsidiaryType;
        }

    }
};
