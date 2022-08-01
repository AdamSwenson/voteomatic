<template>
    <div class="card pool-member-creation-card" >

        <!--        <div class="card-header">-->
        <!--            <div class="h4 card-title">Add someone to the candidate pool</div>-->
        <!--        </div>-->

        <div class="card-body" v-show="showFields">

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
            <button class="btn btn-danger" v-on:click="clearFields">Clear</button>
        </div>

<!--        <div class="card-footer">-->
<!--            <div class="row ">-->

<!--                <div class="col-md-auto">-->
<!--                    <button class="btn btn-info"-->
<!--                            v-on:click="toggleFields"-->
<!--                    ><i class="bi bi-plus"></i> {{ buttonLabel }}</button>-->
<!--                </div>-->

<!--                <div class="col-md-auto">-->
<!--                    <import-pool-controls></import-pool-controls>-->
<!--                </div>-->

<!--            </div>-->

        </div>

    </div>
</template>

<script>

import PoolMember from "../../../../models/PoolMember";

import MeetingMixin from "../../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../../mixins/motionStoreMixin";
import CandidateFieldInput from "../controls/candidate-fields/candidate-field-input";
import ImportPoolControls from "./import/import-pool-controls";

/**
 * This is used to create a pool member for an office. That entails creating a
 * Person and then adding them to the pool
 */
export default {

    name: "pool-member-creation-card",
    components: {ImportPoolControls, CandidateFieldInput},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {
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

        handleCreate: function () {
            let person = this.poolMember;
            let me = this;
            this.$store.dispatch('createPerson', person).then((p) => {
                me.$store.dispatch('addPersonToPool', {person: p, motionId: me.motion.id}).then(() => {
                    me.showFields = false;
                    me.clearFields();
                });

            });
        },

        toggleFields: function () {
            this.showFields = !this.showFields;
        }
    }

}
</script>

<style scoped>

</style>
