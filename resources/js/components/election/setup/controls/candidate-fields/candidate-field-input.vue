<template>
<div class="candidate-field-input">

    <label v-bind:for="fieldName">{{fieldName}}</label>
    <div class="input-group mb-3">
        <input type="text" class="form-control" v-bind:id="fieldName" v-model="fieldVal">
    </div>

</div>
</template>

<script>
export default {
    name: "candidate-field-input",

    props: ['fieldName'],

    mixins: [],

    data: function () {
        return {
            val: ''
        }
    },

    asyncComputed: {},

    computed: {
        fieldId : function(){
            return this.fieldName;
        },
        fieldVal: {
            get : function(){
                return this.val;
            },
            set : function(v){
                this.val = v;
                this.$emit('field-update', {fieldName: this.fieldName, fieldVal : this.val});
            }
        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
