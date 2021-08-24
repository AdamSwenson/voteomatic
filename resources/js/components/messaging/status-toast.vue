<template>
    <div
        aria-live="polite"
         aria-atomic="true"
        style="position: relative; min-height: 200px;"
    >
        <div class="toast" style="position: absolute; top: 0; right: 0;">
            <div class="toast-header">
                <img src="..." class="rounded mr-2" alt="...">
                <strong class="mr-auto">{{headerText}}</strong>
<!--                <small>11 mins ago</small>-->

                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="toast-body">
                {{bodyContent}}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "status-toast",

    props: [],

    mixins: [],

    data: function () {
        return {
            headerText : '',
            bodyContent : '',
            //set as polite or alert depending on use
            ariaRole : 'polite'
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
