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

        }
    },

    motions: {
        /**
         * Path for the resource controller for motions.
         * For create requests, leave the id empty
         * @param motionId
         * @returns {string}
         */
        resource : (motionId=null) =>{
            let base = normalizedRouteRoot() + 'motions/';
            if(_.isNull(motionId)) {
                return base;
            }
            return base + motionId;
        }
    }

}
