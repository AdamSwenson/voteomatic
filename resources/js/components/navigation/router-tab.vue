<template>
    <li class="nav-item mt-1 router-tab-item">
    <router-link
        class="nav-link router-tab-link"
        v-bind:class="{active: isActive, 'router-tab-link--vote': isVoteRoute}"
        v-if="showTab"
        v-bind:to="path"
        v-bind:aria-label="ariaLabel"
        :aria-current="isActive ? 'page' : null"
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
        isActive: function () {
            return this.$router.currentRoute.value.name === this.name;
        },
        isVoteRoute: function () {
            return this.name === 'vote';
        },
    },

}
</script>

<style scoped>
.router-tab-item { flex: 0 0 auto; }
.router-tab-link {
    display: flex;
    align-items: center;
    min-height: 44px;
    padding: .6rem .9rem;
    white-space: nowrap;
    font-weight: 600;
}
.router-tab-link--vote:not(.active) { color: #166534; }
.router-tab-link--vote:not(.active):hover { background: #dcfce7; border-color: #86efac #86efac transparent; }
@media (max-width: 767.98px) {
    .router-tab-link { border: 0; border-radius: .4rem; padding: .55rem .8rem; }
    .router-tab-link.active { background: #1d4ed8; color: #fff; }
    .router-tab-link--vote:not(.active) { background: #dcfce7; color: #166534; }
}
</style>
