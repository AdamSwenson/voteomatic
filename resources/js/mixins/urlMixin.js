import {normalizedRouteRoot} from "../utilities/url.utilities";


const computed = {
        routeRoot: function () {

            return normalizedRouteRoot();

            // return window.routeRoot;
            // return this.$store¢.getters.getRouteRoot;
        }
    };


export default {
    computed,

}
