<template>
    <nav id="pageNav" class="navbar navbar-expand-md navbar-dark shadow-sm " >

        <!--    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">-->
        <div class="container-fluid  ">

            <span class="navbar-brand  mb-0 ms-lg-5 ms-1 h1">{{ meetingName }}</span>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav ms-auto "></ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav me-3">
                    <!--                    <li class="nav-item">-->
                    <!--                        <form class="form-inline">-->
                    <!--                            <refresh-button></refresh-button>-->
                    <!--                        </form>-->
                    <!--                    </li>-->

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown"
                           class="nav-link dropdown-toggle"
                           href="#" role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false"
                        >{{ userName }}</a>

                        <!-- Authentication Links -->
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <!--                        <div class="dropdown-menu dropdown-menu-right"-->
                            <!--                             aria-labelledby="navbarDropdown"-->
                            <!--                        >-->
                            <li>
                                <a class="dropdown-item"
                                   v-if="isDev"
                                   v-bind:href="nonLTILoginUrl">Login</a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   v-on:click="logout"
                                >Logout</a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   v-on:click="home"
                                >Home</a>
                            </li>
                            <!--                        </div>-->
                        </ul>
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
            this.$store.dispatch('logout');
        },
        home: function () {
            this.$store.dispatch('openHomePage');
        }
    }

}
</script>

<style scoped>
/*#pageNav{*/
/*    background-color: #0f6d81;*/
/*    !*padding-left: 100px;*!*/
/*}*/
</style>
