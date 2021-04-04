/**
 * Ensure that the base url ends with a '/'
 * as expected by all the route methods
 * @param url
 */
const normalizedRouteRoot = () => {
    let url = window.routeRoot;

    if (url[url.length] === '/') return url;

    return url + '/';
};


export  {
    normalizedRouteRoot
};



