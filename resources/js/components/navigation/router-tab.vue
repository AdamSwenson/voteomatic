<template>
    <router-link
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
export default {
    name: "router-tab",
    props: ['route'],

    data: function () {
        return {

            activeClass: 'active',
        }
    },
    computed: {
        name: function () {
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
