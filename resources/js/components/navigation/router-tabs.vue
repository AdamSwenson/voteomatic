<template>

    <div class="router-tabs" role="navigation">
    <ul class=" nav nav-tabs">
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
        }
    },


    computed: {

        routes: function () {
            let showRoutes = [];
            let me = this;
            _.forEach(routes, (r) => {
                if (r.adminOnly) {
                    if (me.isAdmin) {
                        showRoutes.push(r);
                    }
                } else {
                    showRoutes.push(r);
                }
            })

            return showRoutes;
            // return routes
        },

    },

    methods: {},


}

</script>
