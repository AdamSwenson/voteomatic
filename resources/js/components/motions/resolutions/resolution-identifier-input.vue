<template>
    <div class="resolution-identifier-input">
    <label for="rezzieIdentifier">Resolution id (required)</label>

    <input  id="rezzieIdentifier" type="text" class="resolution-identifier-input" v-model="identifier">
    </div>
</template>

<script>
import Payload from "../../../models/Payload";

export default {
    name: "resolution-identifier-input",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {
        identifier: {
            get: function () {
                if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
                    return '';
                }
                return this.motion.resolutionIdentifier;
            },
            set(v) {
                // window.console.log('rezzie-input', v);
                //If they cleared the draft and the window is st

                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'resolutionIdentifier',
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
        },
    },

    methods: {}

}
</script>

<style scoped>

</style>
