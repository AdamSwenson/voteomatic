<template>
    <nav class="navbar navbar-expand-md navbar-dark shadow-sm " style="background-color: darkblue;">

<!--    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">-->
        <div class="container-fluid">

            <span class="navbar-brand mb-0 h1">{{ meetingName }}</span>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav ms-auto "></ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav me-3">
                    <li class="nav-item">
                        <form class="form-inline me-3">
                            <whatis></whatis>
<!--                            <refresh-button></refresh-button>-->
                        </form>
                    </li>

<!--                    <li class="nav-item dropdown">-->
<!--                        <a id="navbarDropdown"-->
<!--                           class="nav-link dropdown-toggle"-->
<!--                           href="#" role="button"-->
<!--                           data-bs-toggle="dropdown"-->
<!--                           aria-haspopup="true"-->
<!--                           aria-expanded="false"-->
<!--                        >{{ userName }}</a>-->

<!--                        &lt;!&ndash; Authentication Links &ndash;&gt;-->
<!--                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">-->
<!--&lt;!&ndash;                        <div class="dropdown-menu dropdown-menu-right"&ndash;&gt;-->
<!--&lt;!&ndash;                             aria-labelledby="navbarDropdown"&ndash;&gt;-->
<!--&lt;!&ndash;                        >&ndash;&gt;-->
<!--<li>-->
<!--                            <a class="dropdown-item"-->
<!--                               v-if="isDev"-->
<!--                               v-bind:href="nonLTILoginUrl">Login</a>-->
<!--</li>-->
<!--                            <li>-->

<!--                            <a class="dropdown-item"-->
<!--                               v-on:click="logout"-->
<!--                            >Logout</a>-->
<!--                            </li>-->
<!--&lt;!&ndash;                        </div>&ndash;&gt;-->
<!--                            </ul>-->
<!--                    </li>-->

                </ul>
            </div>
        </div>
    </nav>
</template>

<script>

import * as routes from "../../routes";
import MeetingMixin from '../../mixins/meetingMixin';
import Whatis from "./informational/whatis";

export default {
    name: "pmode-page-navbar",
    components: {Whatis, },
    props: [],

    mixins: [MeetingMixin],

    data: function () {
        return {
            appName: 'voteomatic'
        }
    },

    computed: {

        baseUrl: function () {
            return routes.auth.baseUrl();
        },

        logoutUrl: function () {
            return routes.auth.logout();
        },

        /**
         * Whether running in non-production environment.
         * NOTHING WITH SECURITY IMPLICATIONS SHOULD RELY ON THIS VALUE.
         */
        isDev: function () {
            return window.env === 'local';
        },


        nonLTILoginUrl: function () {
            return routes.auth.nonLTILogin();
        },

        userName: function () {
            return window.userName;
        }

    },
    methods: {

        logout: function () {
            this.$store.dispatch('logout');
        }
    }

}
</script>

<style scoped>

</style>
