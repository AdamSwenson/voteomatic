<template>
    <footer class="bd-footer py-5 mt-5 ">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <ul class="list-unstyled small">

                        <!--                    <ul class="list-unstyled small text-muted">-->
                        <li class="mb-2">Designed and built by Adam Swenson</li>
                        <li class="mb-2">{{ appVersion }}</li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2 offset-lg-1 mb-3"></div>

                <div class="col-6 col-lg-2 mb-3"></div>

                <div class="col-6 col-lg-2 mb-3">

                    <ul class="list-unstyled ">
                        <li class="mb-2">
                            <a href="https://github.com/AdamSwenson/voteomatic#the-voteomatic"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="link-dark"
                            >Documentation</a>
                        </li>

                        <li class="mb-2">
                            <a href="https://github.com/AdamSwenson/voteomatic/issues"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="link-dark"
                            >Feedback</a>
                        </li>
                    </ul>

                </div>

                <div class="col-6 col-lg-2 mb-3"></div>

            </div>
        </div>
    </footer>
</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";

export default {
    name: "page-footer",

    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        appVersion: function () {
            if (isReadyToRock(window.appVersion)) return 'Currently v.' + window.appVersion;
            return ''
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
