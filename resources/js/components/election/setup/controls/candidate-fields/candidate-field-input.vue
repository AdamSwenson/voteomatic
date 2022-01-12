<template>
    <div class="candidate-field-input">

        <label v-bind:for="fieldName">{{ fieldName }}</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" v-bind:id="fieldName" v-model="fieldVal">
        </div>

    </div>
</template>

<script>
export default {
    name: "candidate-field-input",

    props: ['fieldName', 'clearCount'],

    mixins: [],

    data: function () {
        return {
            val: ''
        }
    },

    watch: {
        // When the parent has cleared out the
        //fields it will update this value. That will
        //tell this component to clear out the fields.
        clearCount: function () {
            //We use this rather than setting fieldVal since that
            //would trigger the event broadcast
            this.resetField();
        }
    },

    asyncComputed: {},

    computed: {
        fieldId: function () {
            return this.fieldName;
        },
        fieldVal: {
            get: function () {
                return this.val;
            },
            set: function (v) {
                this.val = v;
                this.$emit('field-update', {fieldName: this.fieldName, fieldVal: this.val});
            }
        }
    },

    methods: {
        /**
         * Resets the input field without triggering the update
         * event
         */
        resetField: function () {
            this.val = '';
        }
    }

}
</script>

<style scoped>

</style>
