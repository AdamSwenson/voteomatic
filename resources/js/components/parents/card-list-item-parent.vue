<template>
    <li class="list-group-item"
        v-if="showRow"
    >
        <button class="btn "
                v-bind:class="styling"
                v-on:click="handleClick"
                v-if="showButton"
        >{{ buttonLabel }}</button>  {{content}}

    </li>

</template>

<script>
export default {
    name: "card-list-item-parent",

    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick : function(){}
    }

}
</script>

<style scoped>

</style>
