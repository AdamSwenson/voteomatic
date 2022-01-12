<template>
    <div class="candidate-field-config-card card">

        <div class="card-header">Fields displayed for all candidates</div>

        <div class="card-body">
<!--            <h3 class="h3">Fields displayed for all candidates</h3>-->
            <ul class="list-group list-group-flush">
                <li class="list-group-item"
                    v-for="field in currentFields"
                    :key="field"
                ><delete-candidate-field-button
                    :field-name="field"
                v-if="universalFields.indexOf(field) === -1"></delete-candidate-field-button>     {{ field }}
                </li>
            </ul>

            <div class="card-text"><p class="text-muted">NB, deleting a field does not
            delete any information you have entered for the candidates. Creating a new field with the
            same name as a deleted field will again display that information.</p></div>
        </div>

        <div class="card-body" v-if="showFields">
            <label for="fieldName">Field name</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="fieldName" v-model="fieldName">
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

import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import DeleteCandidateFieldButton from "./controls/candidate-fields/delete-candidate-field-button";

/**
 * Used to configure which fields are displayed for
 * candidates in an election
 */
export default {
    name: "candidate-field-config-card",
    components: {DeleteCandidateFieldButton},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {
            showFields: false,
            fieldName: '',
            fieldType: '',
            //These cannot be deleted
            universalFields : ['First name', 'Last name']
        }
    },

    asyncComputed: {
        currentFields: function () {
            return _.concat(this.universalFields, this.meeting.candidateFields);
        }
    },

    computed: {
        buttonLabel: function () {
            if (this.showFields) {
                return 'Done';
            }
            return 'Add field to candidates';
        },
    },

    methods: {
        clearFields: function () {
            this.fieldName = '';
            this.fieldType = '';
        },

        handleCreate: function () {

            let me = this;
            this.$store.dispatch('addCandidateField', this.fieldName).then((p) => {
                me.showFields = false;
                me.clearFields();
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
