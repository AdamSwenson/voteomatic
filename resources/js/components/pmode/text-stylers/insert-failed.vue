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

    methods: {},

    mounted() {
        // let pid = document.getElementById(this.itemId);
        // window.console.log(pid);
        // return new bootstrap.Popover(pid);
        //
        // var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        //
        // window.console.log('pop', popoverTriggerList);
        // var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        //     return new bootstrap.Popover(popoverTriggerEl)
        // })
        // this.$nextTick(function () {
        //     var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        //
        //     var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        //         return new bootstrap.Popover(popoverTriggerEl)
        //     })
        // });
    }

}
</script>

<style scoped>

</style>
