<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">

            <a class="navbar-brand"
               v-bind:href="baseUrl">
                {{ appName }}
            </a>

            <!--            <button class="navbar-toggler" type="button" data-toggle="collapse"-->
            <!--                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"-->
            <!--                    aria-label="Toggle navigation">-->
            <!--                <span class="navbar-toggler-icon"></span>-->
            <!--            </button>-->

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">


                    <!--                    <li class="nav-item">-->
                    <!--                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>-->
                    <!--                    </li>-->


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
                               v-on:click="logout">
                                <!--                               href="{{ route('logout') }}"-->
                                <!--                               onclick="event.preventDefault();-->
                                <!--                                                     document.getElementById('logout-form').submit();">-->
                                Logout
                            </a>

                            <!--                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">-->
                            <!--                                @csrf-->
                            <!--                            </form>-->
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</template>

<script>

import * as routes from "../../routes";

export default {
    name: "page-navbar",

    props: [],

    mixins: [],

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
