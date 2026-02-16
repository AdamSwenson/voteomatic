<template>
    <a data-bs-placement="top"
       data-bs-toggle="tooltip"
       v-bind:title="content"
    ><i class="bi-info-circle" role="img" aria-label="Info"></i></a>
</template>

<script>
export default {
    name: "info-tooltip",

    props: [
        'content'
    ],

    mixins: [],

    data: function () {
        return {}
    },


    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
