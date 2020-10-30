<template>
    <div class="alert alert-error"
         role="alert"
         v-if="show"
    >
        <h4 class="alert-heading">Uh oh!</h4>
        <p>{{message}}</p>
        <hr>
        <button type="button" class="btn btn-info" v-on:click="closeAlert">Close</button>

    </div>

</template>

<script>
export default {
    name: "error-alert",

    data : function(){
        return {
            show: false
        }

    },
    props: ['errorCode'],

    computed: {
        message: function () {

        }
    },
    methods : {
        closeAlert: function(){
         this.show = false;
         //in case anything is listening and needs to
        // adjust accordingly
         this.$emit('closing-alert');
        }
    }
}
</script>

<style scoped>

</style>
