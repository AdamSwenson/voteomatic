<!--<template>-->
<!--    <button type="button"-->
<!--            v-bind:class="styling"-->
<!--            v-on:click="handleClick"-->
<!--    >Create amendment</button>-->

<!--</template>-->

<script>
import ButtonParent from "../../parents/button-parent";

export default {
    name: "amendment-button",
    components: {},
    extends: ButtonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            label: 'Create amendment',
            styling: 'btn btn-outline-primary amendment-button'
        }
    },

    computed: {},
    methods: {
        handleClick: function () {
            this.$emit('new-amendment');
        }
    }

}
</script>

<style scoped>

</style>
