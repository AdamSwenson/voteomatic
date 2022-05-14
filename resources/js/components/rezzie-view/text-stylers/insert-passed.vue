<template>
    <span class="border border-success insert-passed">
        <i class="bi bi-pencil"></i> <span v-html="text"></span>
    </span>
</template>

<script>
/**
 * Indicates that the provided text was successfully inserted
 * by a previous motion.
 */
export default {
    name: "insert-passed",

    props: ['text', 'amendmentId'],

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
