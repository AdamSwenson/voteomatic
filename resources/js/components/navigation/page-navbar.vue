<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">

            <a class="navbar-brand"
               v-bind:href="baseUrl">
                {{ appName }}
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto "></ul>

                <span class="navbar-text text-dark text-lg-center">{{meetingName}}</span>

<!--                <span class="navbar-text text-muted ">{{meetingDate}}</span>-->


                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <form class="form-inline">
                            <refresh-button></refresh-button>
                        </form>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        >{{ userName }}</a>

                        <!-- Authentication Links -->
                        <div class="dropdown-menu dropdown-menu-right"
                             aria-labelledby="navbarDropdown"
                        >

                            <a class="dropdown-item"
                               v-if="isDev"
                               v-bind:href="nonLTILoginUrl">Login</a>


                            <a class="dropdown-item"
                               v-on:click="logout"
                            >Logout</a>

                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</template>

<script>

import * as routes from "../../routes";
import MeetingMixin from '../../mixins/meetingMixin';
import RefreshButton from "./refresh-button";

export default {
    name: "page-navbar",
    components: {RefreshButton},
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
            Vue.axios.post(this.logoutUrl);
        }
    }

}
</script>

<style scoped>

</style>
