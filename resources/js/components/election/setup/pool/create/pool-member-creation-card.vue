<template>
    <div class="pool-member-creation-card">

        <create-pool-member-modal :should-close="showModal">

            <div class="card ">

                <div class="card-body">

                    <label class='form-label' for="first-name">First name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="first-name" v-model="firstName">
                    </div>


                    <label class='form-label' for="first-name">Last name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="last-name" v-model="lastName">
                    </div>

                    <candidate-field-input
                        :field-name="f"
                        :clear-count="clearCount"
                        v-for="f in customFields"
                        :key="f"
                        v-on:field-update="handleUpdate"
                    ></candidate-field-input>

                    <button class="btn btn-success" v-on:click="handleCreate">Add</button>
                    <button class="btn btn-danger" v-on:click="handleClear">Clear</button>
                </div>


            </div>


        </create-pool-member-modal>
    </div>
</template>

<script>

import PoolMember from "../../../../../models/PoolMember";

import MeetingMixin from "../../../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../../../mixins/motionStoreMixin";
import CandidateFieldInput from "../../candidates/candidate-fields/candidate-field-input";
import ImportPoolControls from "../import/import-pool-controls";
import CreatePoolMemberModal from "./create-pool-member-modal";

/**
 * This is used to create a pool member for an office. That entails creating a
 * Person and then adding them to the pool.
 *
 * Note that this is wrapped by a modal. We did it this way so that could
 * extend modal parent etc
 */
export default {

    name: "pool-member-creation-card",
    components: {CreatePoolMemberModal, ImportPoolControls, CandidateFieldInput},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {
            showModal: 0,
            showFields: false,
            poolMember: new PoolMember({}),
            //This will be watched by the candidate-field-input
            //children. When it is updated, those will reset their values
            clearCount: 0
        }
    },

    asyncComputed: {},

    computed: {
        buttonLabel: function () {
            if (this.showFields) {
                return 'Done';
            }
            return 'Add person';
        },

        customFields: function () {
            return this.meeting.candidateFields;
        },


        firstName: {
            get: function () {
                return this.poolMember.first_name;
            }, set: function (v) {
                this.poolMember.first_name = v;
            }
        },
        lastName: {
            get: function () {
                return this.poolMember.last_name;
            },
            set: function (v) {
                this.poolMember.last_name = v;
            }

        }
    },

    methods: {
        clearFields: function () {
            this.poolMember = new PoolMember({});
            this.clearCount += 1;
        },

        handleUpdate: function ({fieldName, fieldVal}) {
            window.console.log(fieldName, fieldVal);
            this.poolMember.setInfoField(fieldName, fieldVal);
        },

        handleClear: function () {
            this.clearFields();
            this.closeModal();
        },

        handleCreate: function () {
            let person = this.poolMember;
            let me = this;
            this.$store.dispatch('createPerson', person).then((p) => {
                me.$store.dispatch('addPersonToPool', {person: p, motionId: me.motion.id}).then(() => {
                    me.showFields = false;
                    me.clearFields();
                    me.closeModal();
                });

            });
        },
        closeModal: function () {
            this.showModal += 1;
        },

        toggleFields: function () {
            this.showFields = !this.showFields;
        }
    }

}
</script>

<style scoped>

</style>
