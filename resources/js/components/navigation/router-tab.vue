<template>
    <router-link
        v-if="showTab"
        v-bind:to="path"
        v-slot="{ href, route, navigate, isActive, isExactActive }"
    >
        <li class="nav-item"
            :class="[isActive && 'router-link-active', isExactActive && 'router-link-exact-active']"
        >
            <a class="nav-link"
               :class="[isActive && activeClass, isExactActive && activeClass]"
               :href="href" @click="navigate">{{ label }}</a>
        </li>
    </router-link>

    <!--    <router-link-->
    <!--        v-bind:to="path"-->
    <!--        v-bind:key="r.name"-->
    <!--        v-bind:style="styling"-->
    <!--        v-bind:active-class="activeClass"-->

    <!--    >-->
    <!--        &lt;!&ndash;                <li class="nav-item">&ndash;&gt;-->
    <!--        <a class="page-nav nav-link">-->
    <!--                    <span class="icon is-small">-->
    <!--&lt;!&ndash;                        <svg v-bind:class="r.icon" aria-hidden="true">&ndash;&gt;-->
    <!--                        &lt;!&ndash;                              <use xlink:href="bootstrap-icons.svg#{{r.icon}}"/>&ndash;&gt;-->
    <!--                        &lt;!&ndash;                        </svg>&ndash;&gt;-->
    <!--                        &lt;!&ndash;                        <i v-bind:class="r.icon" aria-hidden="true"></i>&ndash;&gt;-->
    <!--                        </span>-->
    <!--            <span>{{ r.label }}</span>-->
    <!--        </a>-->
    <!--    </router-link>-->

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

    asyncComputed : {
        showTab: function(){
            if(this.name === 'vote'){
                return this.isVotingAllowed && !this.isComplete;
            }

            if(this.name === 'results'){
                return this.isComplete;
            }

            return true;
        }
    },

    computed: {
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

</style>
