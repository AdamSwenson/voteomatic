<template>
    <router-link
        v-if="showTab"
        v-bind:to="path"
        v-slot="{ href, route, navigate, isActive, isExactActive }"
        v-bind:aria-label="ariaLabel"
    >
        <li class="nav-item mt-1"
            :class="[isActive && 'router-link-active', isExactActive && 'router-link-exact-active']"
        >
<!--            <nav v-bind:aria-label="label"></nav>-->
            <a class="nav-link"
               :class="[isActive && activeClass, isExactActive && activeClass]"
               :href="href" @click="navigate">{{ label }}</a>
        </li>
    </router-link>

</template>

<script>
import MotionStoreMixin from "../../mixins/motionStoreMixin";

export default {
    name: "router-tab",
    props: ['route'],

    mixins: [MotionStoreMixin],

    data: function () {
        return {
            activeClass: 'active',
        }
    },


    computed: {
        ariaLabel: function () {
        return `${this.label} Tab`;
        },

        // asyncComputed : {
        showTab: function () {
            if (this.name === 'vote') {
                return this.isVotingAllowed && !this.isComplete;
            }

            if (this.name === 'results') {
                return this.isComplete;
            }

            return true;

        },

        name: function () {
            return this.route.name;
        },
        label: function () {
            return this.route.label;
        },
        path: function () {
            return this.route.path;
        },
        styling: function () {
        },
    }
}
</script>

<style scoped>
.nav a {
    text-decoration: none;
}
</style>
