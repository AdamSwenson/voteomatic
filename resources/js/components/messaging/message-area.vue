<template>
    <div class="message-area">
        <status-alert
            v-for="m in messages"
            :key="m.id"
            :message="m"
        ></status-alert>
    </div>

</template>

<script>
import StatusAlert from "./status-alert";

export default {
    name: "message-area",
    components: {StatusAlert},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        m : function(){
            return this.$store.getters.getMessages;
        },
        messages: { get : function () {
            return this.$store.getters.getMessages;
        },
            watch : ['m']
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
