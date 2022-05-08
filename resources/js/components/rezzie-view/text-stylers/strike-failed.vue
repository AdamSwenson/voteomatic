<template>
  <span class="border border-danger strike-failed">
       <span class="text-danger">
           <i class="bi bi-scissors"></i>
       </span><span v-html="text"></span>

<!--        <a v-bind:href="resultsLink" class="text-decoration-none">-->
<!--            <span v-html="text"></span>-->
<!--        </a>-->
  </span>
</template>

<script>

/**
 * Indicates that there was an attempt to strike this text which failed.
 */
export default {

    name: "strike-failed",

    props: ['text', 'amendmentId'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {
        resultsLink: function () {
            //returns a link that will show results for the amendmentId
        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
