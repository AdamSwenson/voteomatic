<template>

    <div class="vote-required-inputs">

        <div class="form-group">

            <label for="requiresSelect">Vote required to pass</label>

            <select
                id="requiresSelect"
                class="form-control "
                v-model="requires"
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
            <label for="requiresOther">Enter the percentage which the vote count must exceed</label>
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
import Payload from "../../../../models/Payload";
import MeetingMixin from "../../../storeMixins/meetingMixin";
import MotionMixin from "../../../storeMixins/motionMixin";

export default {
    name: "vote-required-inputs",

    props: [],

    mixins: [MeetingMixin, MotionMixin],

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

    asyncComputed: {


        customRequires: {
            get: function () {
                try {
                    return 100 * this.motion.requires;
                } catch (e) {
                    return ''
                }
            },
            set(v) {

                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'requires',
                        'updateVal': _.toNumber(v)
                    }
                );

                this.$store.dispatch('updateMotion', p);

            }

        },


        requires: {
            get: function () {
                try {
                    // let r = _.toNumber(this.motion.requires);
                    // let standardPcts = [0, 0.5, 0.66]
                    // window.console.log('requ', r, standardPcts.indexOf(r));
                    //
                    // if(_.isUndefined(r) || _.isNull(r)){
                    //     return '';
                    // }
                    // if (standardPcts.indexOf(r) === -1) {
                    //
                    //     //show the custom field
                    //     this.showCustomRequires = true;
                    // }

                    return this.motion.requires;
                } catch (e) {
                    return ''
                }
            },
            set(v) {

                if (v === 'other') {
                    this.showCustomRequires = true;

                } else {

                    let p = Payload.factory({
                            'object': this.motion,
                            'updateProp': 'requires',
                            'updateVal': _.toNumber(v)
                        }
                    );

                    this.$store.dispatch('updateMotion', p);

                }

            }
        },

    }

}
</script>

<style scoped>

</style>
