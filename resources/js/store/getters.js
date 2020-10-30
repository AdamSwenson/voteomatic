/**
 * Created by adam on 2020-07-13.
 */

module.exports = {

    getIsAdmin: (state) => {
        return state.isAdmin;
    },

    getRouteRoot: (state) => {
        return window.routeRoot;
    },
}
