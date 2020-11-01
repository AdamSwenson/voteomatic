/**
 * Ensure that the base url ends with a '/'
 * as expected by all the route methods
 * @param url
 */
const normalizedRouteRoot = () => {
    let url = window.routeRoot;

    if(url[url.length] === '/') return url;

    return url + '/';
}

/**
 * This holds all information about which
 * routing urls are used for what purposes.
 *
 */
module.exports = {

    results : {
        getCounts : (motionId) => {
            return normalizedRouteRoot() + 'results/' + motionId + '/counts'
        },

        getResults : (motionId) => {
          return normalizedRouteRoot() + 'results/' + motionId;
        }
    },

    votes : {
        recordVote : (motionId) => {
            return normalizedRouteRoot() + 'record-vote/' + motionId;
        }
    },

    castVotes: {

        getVotedMotions : (meetingId) => {
            return normalizedRouteRoot() + 'cast-votes/' + meetingId;

        }
    },

    receipts : {
        validateReceipt : () => {
            return normalizedRouteRoot() + 'validation';
        }
    },

    meetings: {
        /**
         * Path for the resource controller for meetings.
         * For create requests, leave the id empty
         * @param meetingId
         * @returns {string}
         */
        resource : (meetingId=null) =>{
            let base = normalizedRouteRoot() + 'meetings/';
            if(_.isNull(meetingId)) {
                return base;
            }
            return base + meetingId;

        },

        getRoster : (meetingId) => {
            let base = normalizedRouteRoot() + 'roster/';
            if(_.isNull(meetingId)) {
                return base;
            }
            return base + meetingId;

        }
    },

    motions: {
        /**
         * Path for the resource controller for motions.
         *
         * In the unlikely circumstance that we want to make a create
         * request which doesn't associate with a meeting, leave the id empty
         *
         * @param motionId
         * @returns {string}
         */
        resource : (motionId=null) =>{
            let base = normalizedRouteRoot() + 'motions/';
            if(_.isNull(motionId)) {
                return base;
            }
            return base + motionId;
        },

        createMotion: (meetingId) => {
            return normalizedRouteRoot() + 'motions/meeting/' + meetingId;
        },

        endVoting: (motionId) => {
        return normalizedRouteRoot() + 'motions/close/' + motionId;
        },

        getCurrentMotion : (meetingId) => {
            return normalizedRouteRoot() + 'motions/stack/' + meetingId;
        },

        getAllMotionsForMeeting: (meetingId) => {

            return normalizedRouteRoot()  + 'motions/meeting/' + meetingId;
        }

    }

}
