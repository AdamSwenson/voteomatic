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
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" >
                    <div class="form-group">
                        <label for="writeInFirst">First name</label>
                        <input type="text"
                               class="form-control"
                               id="writeInFirst"
                               aria-describedby="writeInHelp"
                               v-model="firstName"
                        >

                        <label for="writeInLast">Last name</label>
                        <input type="text"
                               class="form-control"
                               id="writeInLast"
                               v-model="lastName"
                        >

                        <small id="writeInHelp" class="form-text text-muted"
                        >Enter the person's name here. If you change your mind or notice a mistake after clicking done, simply de-select them like any other candidate</small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                    >Cancel
                    </button>

                    <button type="button"
                            class="btn btn-primary"
                            data-dismiss="modal"
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

/**
 * Note, this will require that the delete-meeting-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "write-in-modal",

    props: [],

    mixins: [MotionMixin],


    data: function () {
        return {
        _firstName: '',
            _lastName: ''
        }
    },

    computed: {
        modalId: function () {
            return "writeInModal"
        },

        labelId: function () {
            return "writeInModalLabel";
        },


        firstName: {
            get: function () {
                return this._firstName;
            },

            set: function (v) {
                this._firstName = v;
            }
            },

        lastName: {
            get: function () {
                return this._lastName;
            },

            set: function (v) {
                this._lastName = v;
            }
        },

    },

    methods: {
         clearFields : function(){
           this._firstName = '';
           this._lastName = '';
         },

        handleClick: function () {
            let d = {
                first_name: this._firstName,
                last_name : this._lastName,
                info: '',
                motionId: this.motion.id
            };

            this.$store.dispatch('addWriteInCandidateToOfficeElection', d).then(() => {

            });

            //
            //     let me = this;
            //
            //     //First we create and store a new meeting from the
            //     //provided template
            //     let p = this.$store.dispatch('deleteMeeting', me.meeting)
            //         .then(function () {
            //         });
            // }

        }

    }
}

</script>

<style scoped>

</style>
