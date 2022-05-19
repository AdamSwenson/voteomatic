<template>
    <div v-if="showAlert" class="alert alert-warning alert-dismissible fade show" role="alert">
        <p><strong>Warning</strong> This isn't fully compatible with Safari. For amendments,
        the text being changed may not be correctly displayed in the motion stack on the right.</p>
        <p>This will work properly on Chrome and Firefox</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</template>

<script>
export default {
    name: "safari-regex-warning",

    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {
        showAlert: function(){
            var isLookBehindSupported = false;

            try {
                new RegExp("(?<=)");
                isLookBehindSupported = true;
            } catch (err) {
                // If the agent doesn't support look behinds, the attempted
                // creation of a RegExp object using that syntax throws and
                // isLookBehindSupported remains false.
            }
            return ! isLookBehindSupported;

            // var splitUpString = isLookBehindSupported ? function(str) {
            //     return (""+str).split(new RegExp("(?<=[A-Z])"));
            // } : function(str) {
            //     return str.replace(/[A-Z]/g,"z$1").split(/z(?=[A-Z])/g);
            // };

        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
