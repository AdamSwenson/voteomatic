<template>

    <!-- Modal -->
    <div class="modal fade"
         v-bind:id="modalId"
         tabindex="-1"
         v-bind:aria-labelledby="labelId"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        v-bind:id="labelId"
                    >Add write-in candidate</h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>

<!--                    <button type="button"-->
<!--                            class="btn-close"-->
<!--                            data-bs-dismiss="modal"-->
<!--                            aria-label="Close">-->
<!--                        <span aria-hidden="true">&times;</span>-->
<!--                    </button>-->
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label  class='form-label' for="writeInFirst">First name</label>
                        <input type="text"
                               class="form-control"
                               id="writeInFirst"
                               aria-describedby="writeInHelp"
                               v-model="firstName"
                        >

                        <label  class='form-label' for="writeInLast">Last name</label>
                        <input type="text"
                               class="form-control"
                               id="writeInLast"
                               v-model="lastName"
                        >

                        <small id="writeInHelp" class="form-text text-muted"
                        >Enter the person's name here. If you change your mind or notice a mistake after clicking done,
                            simply de-select them like any other candidate</small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            v-on:click="clearFields"
                    >Cancel
                    </button>


                    <button type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                            v-on:click="handleClick"
                    >Done
                    </button>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

import MotionMixin from "../../../mixins/motionStoreMixin";


export default {
    name: "write-in-modal",

    props: [],

    mixins: [MotionMixin],


    data: function () {
        return {
            firstName: '',
            lastName: ''
        }
    },

    watch: {},

    computed: {
        modalId: function () {
            return "writeInModal"
        },

        labelId: function () {
            return "writeInModalLabel";
        },

    },

    methods: {
        clearFields: function () {
            this.firstName = '';
            this.lastName = '';
        },

        handleClick: function () {
            let me = this;
            let d = {
                first_name: this.firstName,
                last_name: this.lastName,
                info: {},
                motionId: this.motion.id
            };

            this.$store.dispatch('addWriteInCandidateToOfficeElection', d).then(() => {
                me.clearFields();
            }).catch((e) => {
            me.clearFields();
            });
        }

    }
}

</script>

<style scoped>

</style>
