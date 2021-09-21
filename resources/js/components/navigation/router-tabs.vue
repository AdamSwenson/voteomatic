<template>

    <div class="router-tabs" role="navigation">
        <ul class=" nav nav-tabs ">
            <router-tab v-for="r in routes" :route="r" :key="r.name"></router-tab>

            <!--        <router-link-->
            <!--            v-for="r in routes"-->
            <!--            v-bind:key="r.name"-->
            <!--            tag="li"-->
            <!--            v-bind:style="styling"-->
            <!--            v-bind:active-class="activeClass"-->
            <!--            v-bind:to="r.path"-->
            <!--        >-->
            <!--            &lt;!&ndash;                <li class="nav-item">&ndash;&gt;-->
            <!--            <a class="page-nav nav-link">-->
            <!--                    <span class="icon is-small">-->
            <!--&lt;!&ndash;                        <svg v-bind:class="r.icon" aria-hidden="true">&ndash;&gt;-->
            <!--&lt;!&ndash;                              <use xlink:href="bootstrap-icons.svg#{{r.icon}}"/>&ndash;&gt;-->
            <!--&lt;!&ndash;                        </svg>&ndash;&gt;-->
            <!--&lt;!&ndash;                        <i v-bind:class="r.icon" aria-hidden="true"></i>&ndash;&gt;-->
            <!--                        </span>-->
            <!--                <span>{{ r.label }}</span>-->
            <!--            </a>-->
            <!--        </router-link>-->

        </ul>
    </div>


</template>

<style lang="scss">
.item-card-navigation-tabs {

}


</style>
<script>

import {routes} from '../../routes.client';
import RouterTab from "./router-tab";

import {isReadyToRock} from "../../utilities/readiness.utilities";

/**
 * Page navigation tabs
 * .
 */
export default {
    components: {RouterTab},
    props: [],

    data: function () {
        return {

            adminOnly: [],

            activeClass: 'active',
            styling: 'nav-item',

            identifier: 'page-nav-tabs',

        };
    },

    asyncComputed: {
        isAdmin: {
            get: function () {
                return this.$store.getters.getIsAdmin;
            },
            default: false
        },

        settingsObject : function(){
            return this.$store.getters.getSettings;
        },


        routes: function () {
            let showRoutes = [];
            let me = this;
            _.forEach(routes, (r) => {
                window.console.log(r.name, me.passesAdminCheck(r),  me.passesSettingChecks(r));
                if(me.passesAdminCheck(r) && me.passesSettingChecks(r)){
                    showRoutes.push(r);
                }
                //
                // if (r.adminOnly) {
                //     if (me.isAdmin) {
                //         showRoutes.push(r);
                //     }
                // } else {
                //     showRoutes.push(r);
                // }
            });

            return showRoutes;
            // return routes
        },
    },


    computed: {



    },

    methods: {
        /**
         * Shortcut check of adminOnly
         *
         * If not adminOnly, returns true
         * If adminONly and user is admin, returns true
         * @param route
         */
        passesAdminCheck: function (route) {
            //if no setting is defined for adminOnly, passes
            if (!isReadyToRock(route.adminOnly)) return true;
            //If it's not admin only, check passes
            if(! route.adminOnly) return true;
            window.console.log( 'admin', route.adminOnly , this.isAdmin);
            //If it is admin only we need to check that the user is admin
            if (route.adminOnly && this.isAdmin) return true;

            return false;
        },

        passesSettingChecks: function (route) {
            //no settings are defined for the route
            // if (!isReadyToRock(route.showIfSettings)) return true;
            if(! isReadyToRock(this.settingsObject)) return false;
if(! isReadyToRock(route.showIfSettings)) return true;
            if (route.showIfSettings.length === 0) return true;
       return this.settingsObject.isAnySettingTrue(route.showIfSettings);
                //  _.forEach(route.showIfSettings, (s) => {
                //     window.console.log(settingsObj[s]);
                //     //dev or is is better to define by the condition being false?
                //     if (settingsObj[s] === true) {
                //     return true;}
                //     else {
                //         return true;
                //     }
                // });

        }
    },


}

</script>
