<template>
    <div class="alert alert-dismissible fade show "
         v-if="show"
         v-bind:id="alertId"
         v-bind:class="styling"
         role="alert">
        <p>
            {{ messageText }}
        </p>
        <!--    <hr>-->
        <p class="ml-5"
           v-if="showMotionText">
            {{ motionText }}
        </p>

        <button type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
                v-on:click="handleClick"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";

export default {
    name: "status-alert",

    props: ['message'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        show: function () {
            //This is utterly ridiculous. But I can't figure
            //out why vue won't remove this component when
            //the message is removed from the list
            if (!isReadyToRock(this.message)) return false;
            if (_.isNull(this.message)) return false;
            if (!isReadyToRock(this.messages)) return false;
            let idx = this.messages.indexOf(this.message);
            return idx > -1;
        },
        alertId: function () {
            return _.camelCase('statusAlert' + this.message.id);
        },
        displayTime: function () {
            return this.message.displayTime;
        },
        styling: function () {
            return "alert-" + this.message.messageStyle;
        },
        messageText: function () {
            return this.message.messageText;
        },

        motionText: function () {
            if (isReadyToRock(this.message.motion)) {
                return this.message.motion.content;
            }
        },

        messages: function () {
            return this.$store.getters.getMessages;
        },
        showMotionText: function(){
            return isReadyToRock(this.motionText) && this.motionText.length > 0;
        },

    },


    computed: {},

    watch : {
        alertId : function(){
            if(isReadyToRock(this.alertId)) this.focusAlert();
        }
    },

    methods: {
        handleClick: function () {
            this.$store.commit('removeFromMessageQueue', this.message);
        },

        focusAlert : function(){
            document.body.scrollTop = document.documentElement.scrollTop = 0;

        }
    },


    mounted() {

    }

}
</script>

<style scoped>

</style>
