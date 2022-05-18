<template>
    <div class="resolution-title-input">
    <label for="rezzieTitle">Resolution title</label>
    <input id="rezzieTitle" type="text" class="resolution-title-input" v-model="title">
    </div>
</template>

<script>
import Payload from "../../../models/Payload";

export default {
    name: "resolution-title-input",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {
        title: {
            get: function () {
                if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
                    return '';
                }
                return this.motion.title;
            },
            set(v) {
                // window.console.log('rezzie-input', v);
                //If they cleared the draft and the window is st

                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'title',
                        'updateVal': v
                    }
                );
                //
                // if(isReadyToRock(this.editMode) && this.editMode===true){
                //     this.$emit('update:content', p.updateVal);
                // }else{
                this.$store.dispatch('updateDraftMotion', p);
                // }

            }
        },},

    methods: {}

}
</script>

<style scoped>

</style>
