<template>

    <div class="modal fade"
         v-bind:id="modalId"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="modalLabelId"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title"
                        v-bind:id="modalLabelId"
                    >{{ headerText }}</h5>

                    <button type="button"
                            class="btn-close"
                            v-on:click="closeModal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <div class="modal-body">

                    <div class="body-text" v-html="bodyText">

                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn leftButton"
                            v-bind:class="leftButtonStyling"
                            v-on:click="handleLeftClick"
                    >{{leftButtonLabel}}
                    </button>

                    <button type="button"
                            class="btn rightButton"
                            v-bind:class="rightButtonStyling"
                            v-on:click="handleRightClick"
                    >{{rightButtonLabel}}</button>

<!--                    <slot name="buttonArea"></slot>-->
                </div>

            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "js-controlled-modal-parent",

    props: [],

    mixins: [],

    data: function () {
        return {
            // leftButtonStyling: "",
            // rightButtonStyling : "",
            // modalId : '',
            // headerText : "",
            // rightButtonLabel : "",
            // leftButtonLabel : ""
        }
    },

    asyncComputed: {
        bodyText : function(){},
    },

    computed: {

        modalLabelId : function(){
            return this.modalId + 'Label';
        },

    },

    methods: {
        closeModal : function(){
            $('#' + this.modalId).modal('hide');
        },

        openModal : function(){
            window.console.log('showing ', this.modalId);
            $('#' + this.modalId).modal();
            $('#' + this.modalId).modal('show');
        },

        handleLeftClick : function(){},
        handleRightClick : function(){},

    }

}
</script>

<style scoped>

</style>
