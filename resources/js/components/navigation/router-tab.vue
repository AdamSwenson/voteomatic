<template>
    <li class="nav-item mt-1">
    <router-link
        class="nav-link "
        v-bind:class="styling"
        v-if="showTab"
        v-bind:to="path"
        v-bind:aria-label="ariaLabel"
    >{{label}}
<!--        <li class="nav-item mt-1"-->
<!--            :class="[isActive && 'router-link-active', isExactActive && 'router-link-exact-active']"-->
<!--        >-->
<!--            <a class="nav-link"-->
<!--               :class="[isActive && activeClass, isExactActive && activeClass]"-->

<!--            >{{ label }}</a>-->
<!--        </li>-->
    </router-link>
    </li>

</template>

<script>
import MotionStoreMixin from "../../mixins/motionStoreMixin";

export default {
    name: "router-tab",
    props: ['route'],

    mixins: [MotionStoreMixin],

    data: function () {
        return {
            activeClass: 'active ',
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
            if(this.$router.currentRoute.value.name === this.name) return this.activeClass;

        },
    },

}
</script>
