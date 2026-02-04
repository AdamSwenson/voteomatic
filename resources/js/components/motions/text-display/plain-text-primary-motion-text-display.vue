<template>

    <div class="plain-text-primary-motion-text-display"
    v-bind:class="styling">
        <span >   {{ motion.content }}   </span>
    </div>

</template>

<script>
export default {
    name: "plain-text-primary-motion-text-display",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },


    computed: {
        styling: function () {
            return '';
        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
