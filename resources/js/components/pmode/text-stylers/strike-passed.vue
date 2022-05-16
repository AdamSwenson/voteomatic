<template>
    <span
          class="strike-passed text-success"
    >
        <a
            tabindex="0"
            data-bs-toggle="popover"
            data-bs-trigger="focus"
            v-bind:data-bs-content="popoverContent"
        ><i class="bi " v-bind:class="icon"></i></a>
        </span>
</template>

<script>
/**
 * Indicates that text was successfully struck from this location
 */
export default {
    name: "strike-passed",

    props: ['text', 'amendmentId'],

    mixins: [],

    data: function () {
        return {
            icon: 'bi-scissors'
        }
    },

    asyncComputed: {
        popoverContent: function () {
            return this.text;
        },
        itemId : function () {
            return 'strikePassedAmendment' + this.amendmentId;
        }
    },

    computed: {
        // containerStyling: function () {
        //     return this.name + this.containerStyle;
        // }
    },

    methods: {},


}
</script>

<style scoped>

</style>
