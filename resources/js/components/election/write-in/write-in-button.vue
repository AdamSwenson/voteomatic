<template>
    <button
        v-bind:class="styling"
        data-toggle="modal"
        v-bind:data-target="target"
        v-on:click="handleClick"
    >{{ label }}
    </button>


</template>

<script>

export default {
    name: "write-in-button",

    props: [],

    data: function () {
        return {
            styling: "btn btn-warning "
        }
    },

    computed: {

        modalId: function () {
            return "writeInModal"
        },

        target: function () {
            return '#' + this.modalId;
        },

        label: function () {
            return 'Add write in candidate';
        },

        // typeCapitalized : function(){
        //     return this.type.toUpperCase();
        // },
        //

    },
    methods: {

        /**
         * While the button just toggles the modal we may
         * also want to add other things that are triggered when
         * the modal is shown
         */
        handleClick: function () {
        }
    }
}
</script>

<style scoped>

</style>
