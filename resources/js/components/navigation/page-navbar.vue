<template>
    <nav id="pageNav"
         class="navbar navbar-expand-md navbar-dark shadow-sm "
         aria-label="Top level navigation bar. Only used for logging out or viewing other meetings and elections"
    >

        <div class="container-fluid  ">

            <span class="navbar-brand mb-0 ms-lg-5 ms-1 meeting-name">{{ meetingName }}</span>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Open account menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto"></ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav me-3">

                    <li class="nav-item dropdown">
                        <button id="navbarDropdown"
                           class="nav-link dropdown-toggle"
                           type="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false"
                        >{{ userName }}</button>

                        <!-- Authentication Links -->
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item"
                                   v-if="isDev"
                                   v-bind:href="nonLTILoginUrl">Login</a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="#"
                                   v-on:click.prevent="logout"
                                >Logout</a>
                            </li>

                            <li>
                                <a class="dropdown-item"
                                   href="#"
                                   v-on:click.prevent="home"
                                >Home</a>
                            </li>
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
.meeting-name {
    max-width: calc(100% - 4.5rem);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: clamp(1.1rem, 3vw, 1.65rem);
}

@media (max-width: 767.98px) {
    #pageNav { padding: .55rem .25rem; }
    .navbar-collapse { padding: .75rem 1rem .25rem; }
    .navbar-nav { border-top: 1px solid rgba(255,255,255,.25); padding-top: .5rem; }
    .nav-link { min-height: 44px; text-align: left; }
    .dropdown-menu { width: 100%; margin-top: .25rem; }
}
</style>
