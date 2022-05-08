<template>
    <span class="insert-failed text-danger">
    <a
        tabindex="0"
        data-bs-toggle="popover"
        data-bs-trigger="focus"
        v-bind:data-bs-content="popoverContent"
    >
      <i class="bi bi-pencil"></i>
    </a>
        </span>
</template>

<script>

/**
 * Indicates that there was an attempt to insert text at this point.
 */
export default {
    name: "insert-failed",

    props: ['text',  'amendmentId'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        popoverContent: function(){
            return this.text;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
