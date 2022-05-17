<template>
    <span class="unstyled-text"><slot></slot></span>

<!--&lt;!&ndash;    &ndash;&gt;<span class="unstyled-text" v-html="text"></span>-->
</template>

<script>

/**
 * Used when there is a component present for styling
 * but don't want to style. E.g., for past amendments in the
 * vote display
 */
export default {
    name: "unstyled-text",

    props: [
        // 'text',
        'amendmentId'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
