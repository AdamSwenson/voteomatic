/**
 * For any component that needs access to the
 * current meeting.
 *
 * @type {{computed: {}}}
 */
// import {isReadyToRock} from "../utilities/readiness.utilities";

module.exports = {

    computed: {



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
        },

        subsidiaryTypeCapitalized : function(){
            if (_.isUndefined(this.meeting) || _.isNull(this.meeting)) return '';
            return  _.capitalize(this.meeting.subsidiaryType);
        }

    }
};
