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
      getResults : (motionId) => {
          return normalizedRouteRoot() + ''
      }
    },

    votes : {
        recordVote : (motionId) => {
            return normalizedRouteRoot() + 'record-vote/' + motionId;
        }
    },

}
