<template>

    <ul class="page-navigation-tabs nav nav-tabs is-centered is-boxed">

        <router-link
            v-for="r in routes"
            v-bind:key="r.name"
            tag="li"
            v-bind:style="styling"
            v-bind:active-class="activeClass"
            v-bind:to="r.path"
        >
            <!--                <li class="nav-item">-->
            <a class="page-nav nav-link">
                    <span class="icon is-small">
                            <i v-bind:class="r.icon" aria-hidden="true"></i>
                        </span>
                <span>{{ r.label }}</span>
            </a>
        </router-link>

    </ul>


</template>

<style lang="scss">
.item-card-navigation-tabs {

}


</style>
<script>

import {routes} from '../../routes.client';


/**
 * Page navigation tabs
 * .
 */
export default {
    props: [],

    data: function () {
        return {
            activeClass: 'active',
            styling: 'nav-item',

            identifier: 'page-nav-tabs',

        };
    },


    computed: {

        routes: function () {
            return routes
        },

    },

    methods: {},


}

</script>
