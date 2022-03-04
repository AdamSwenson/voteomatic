<template>

    <div class="vote-required-inputs">

        <div class="form-group">

             <label class='form-label' for="requiresSelect">Vote required to pass</label>

            <select
                id="requiresSelect"
                class="form-control "
                v-model="requiredVote"
            >
                <option disabled value="">Please select required vote</option>
                <option value="0.5">Majority</option>
                <option value="0.66">Two-thirds</option>
                <option value="other">Other</option>

            </select>

        </div>

        <div class="form-group"
             v-if="showCustomRequires"
        >
             <label class='form-label' for="requiresOther">Enter the percentage which the vote count must exceed</label>
            <div class="input-group input-group-sm">

                <input type="number"
                       id="requiresOther"
                       class="form-control"
                       aria-describedby="requiresOtherHelp"
                       placeholder="50"
                       aria-label="Other required vote percentage"
                       v-model="customRequires">

                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2">%</span>
                </div>

            </div>
            <p class="text-muted">
                <!--                        <small id="requiresOtherHelp" class="form-text text-muted">-->
                E.g., a majority must be greater than 50% of the total, so you would enter 50
                <!--                        </small>-->
            </p>
        </div>

    </div>

</template>

<script>
import Payload from "../../../models/Payload";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "vote-required-inputs",

    props: ['motion', 'editMode'],

    mixins: [MeetingMixin], //, MotionMixin],

    data: function () {
        return {
            //whether to display the input for
            //adding a custom vote requirement
            showCustomRequires: false,

        }
    },

    watch : {
        requires : function(){
            let r = _.toNumber(this.motion.requires);
            let standardPcts = [0, 0.5, 0.66]
            window.console.log('requ', r, standardPcts.indexOf(r));

            if(_.isUndefined(r) || _.isNull(r)){
                return '';
            }
            if (standardPcts.indexOf(r) === -1) {

                //show the custom field
                this.showCustomRequires = true;
            }
        }
    },

    computed: {


        customRequires: {
            get: function () {
                try {
                    return 100 * this.motion.requires;
                } catch (e) {
                    return ''
                }
            },
            set(v) {
                let val = _.toNumber(v) / 100
                window.console.log('custom requires', v, val);

                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'requires',
                        'updateVal': val
                    }
                );

                // this.$store.dispatch('updateMotion', p);
                this.$emit('update:requires', p.updateVal);

            }

        },


        requiredVote: {
            get: function () {
                if(this.showCustomRequires) return 'other'
                try {
                    return this.motion.requires;
                } catch (e) {
                    return ''
                }
            },
            set(v) {
                window.console.log(v);
                if (v === 'other') {
                    this.showCustomRequires = true;

                } else {
                    this.showCustomRequires = false;

                    let p = Payload.factory({
                            'object': this.motion,
                            'updateProp': 'requires',
                            'updateVal': _.toNumber(v)
                        }
                    );
                    if(isReadyToRock(this.editMode) && this.editMode===true){
                        this.$emit('update:content', p.updateVal);
                    }else{
                        this.$store.dispatch('updateDraftMotion', p);
                    }


                    // this.$store.dispatch('updateMotion', p);
//                    this.$emit('update:requires', p.updateVal);

                }

            }
        },

    }

}
</script>

<style scoped>

</style>
