<template>
    <div class="card pool-member-creation-card" style="width: 25rem;">

<!--        <div class="card-header">-->
<!--            <div class="h4 card-title">Add someone to the candidate pool</div>-->
<!--        </div>-->

        <div class="card-body" v-show="showFields">

            <label for="first-name">First name</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="first-name" v-model="firstName">
            </div>


            <label for="first-name">Last name</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="last-name" v-model="lastName">
            </div>

            <button class="btn btn-success" v-on:click="handleCreate">Add</button>
            <button class="btn btn-danger" v-on:click="clearFields">Clear</button>
        </div>

        <div class="card-footer">
            <button class="btn btn-info" v-on:click="toggleFields">{{ buttonLabel }}</button>
        </div>

    </div>
</template>

<script>

import PoolMember from "../../../models/PoolMember";

import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
/**
 * This is used to create a pool member for an office. That entails creating a
 * Person and then adding them to the pool
 */
export default {
    name: "pool-member-creation-card",

    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {
            showFields: false,
            firstName: '',
            lastName: ''
        }
    },

    asyncComputed: {},

    computed: {
        buttonLabel: function () {
            if (this.showFields) {
                return 'Done';
            }
            return 'Add person to pool';
        },
        // firstName : function(){
        //
        // },
        // lastName : function(){
        //
        // }
    },

    methods: {
        clearFields: function () {
            this.firstName = '';
            this.lastName = '';
        },

        handleCreate: function () {
            let person = new PoolMember({
                first_name: this.firstName,
                last_name: this.lastName
            });
            let me = this;
            this.$store.dispatch('createPerson', person).then((p) => {
                me.$store.dispatch('addPersonToPool', {person: p, motionId : me.motion.id}).then(() => {
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
