<template>

    <div class="amendment-setup-area card">

        <div class="card-body">
            <div class="row display-area text-center">
                <div class="col">

                    <amendment-text-display :amendment-text="localText"
                                            :original-text="originalText"
                    ></amendment-text-display>

                    <!--                    <h4>Amendment display</h4>-->
                    <!--                    <div class="clearfix"></div>-->
                    <!--                    <p v-html="taggedNewText"></p>-->
                </div>
            </div>

            <div class="row edit-area">
                <div class="col">
                    <div class="form-group">

                        <label for="editText">Edit amendment</label>

                        <textarea id="editText"
                                  class="form-control"
                                  v-model="text"
                                  cols="30"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button class="btn btn-danger"
                            v-on:click="handleClick"
                    >Propose Amendment
                    </button>
                </div>
            </div>

        </div>
    </div>

</template>

<script>

import MotionMixin from "../storeMixins/motionMixin";
import MeetingMixin from "../storeMixins/meetingMixin";
import Payload from "../../models/Payload";
import AmendmentTextDisplay from "./amendment-text-display";

export default {
    name: "amendment-setup-area",
    components: {AmendmentTextDisplay},
    props: [],

    mixins: [MotionMixin, MeetingMixin],

    data: function () {
        return {

            localText: '',

            /**
             * Classes to attach to a word for different purposes
             */
            tags: {
                altered: 'text-monospace',
                inserted: 'text-danger',
                struck: 'struck',
            }
        }
    },

    asyncComputed: {
        originalText: function () {
            if (_.isUndefined(this.motion)) return ''
            return this.motion.content;

        }
    },

    computed: {

        text: {
            get: function () {
                if (this.localText === '') {
                    if (_.isUndefined(this.motion)) return ''
                    this.localText = this.motion.content;
                }
                return this.localText;

            },
            set: function (v) {
                this.localText = v;
            }
        },

        // splitOrigText: function () {
        //     if (_.isUndefined(this.motion)) return ''
        //     return _.words(this.motion.content, /[^, ]+/g);
        // },
        //
        // splitNewText: function () {
        //     return _.words(this.localText, /[^, ]+/g);
        // },
        //
        //
        // taggedNewText: function () {
        //     let out = "";
        //     //whichever is longer to avoid truncating output
        //     let maxIdx = (this.splitOrigText.length > this.splitNewText.length) ? this.splitOrigText.length : this.splitNewText.length;
        //
        //     for (let i = 0; i < maxIdx; i++) {
        //         if (this.splitNewText[i] !== this.splitOrigText[i]) {
        //             //something has changed
        //             out += " <span class='text-danger'>";
        //             out += this.splitNewText[i];
        //             out += "</span>";
        //         } else {
        //             out += " " + this.splitNewText[i];
        //         }
        //
        //     }
        //
        //     //todo this won't actually help since other users won't have access
        //     let pl = Payload.factory({
        //         updateProp: 'taggedAmendmentText',
        //         updateVal: out
        //     })
        //     this.$store.commit('setMotionProp', pl);
        //
        //     return out;
        //
        //
        // }


    },

    methods: {
        handleClick: function () {
            let payload = {
                meetingId: this.meeting.id,
                applies_to: this.motion.id,
                content: this.localText,
                type: 'amendment',
                requires: 0.5
            };

            let p = this.$store.dispatch('createSubsidiaryMotion', payload);
            let me = this;
            p.then(() => {
            });

        },

        // splitText: function () {
        //
        //     _.words(this.originalText, /[^, ]+/g);
        //
        //
        // },
        //
        //
        // tagWord: function (originalWord, newWord) {
        //
        //
        // },
        //
        // /**
        //  * An insertion or strike and insertion has occurred
        //  */
        // handleNewLarger: function (oldBag, newBag) {
        //
        // },
        //
        // check: function (oldText, newText) {
        //     //Array of True/False corresponding to word indexes in new Text
        //     // True indicates that has changed
        //     let out = [];
        //     let checkIdx = 0;
        //
        //     for (let i = 0; i < this.newText.length; i++) {
        //         if (i > 0) {
        //             //check whether the last word was changed
        //
        //             if (out[i - 1 === true]) {
        //
        //             }
        //             //set the indexer for the old text to be the same
        //
        //         }
        //         if (newText[i] == oldText[i]) {
        //             //no change
        //             out.push(false);
        //         }
        //
        //         let checkIdx = i + 1;
        //         return this.splitNewText[checkIdx] !== this.splitOrigText[i]
        //
        //     }
        //
        //
        // },
        //
        // /**
        //  * Check whether the
        //  * @param newWordIndex
        //  */
        // isInsertion: function (newWordIndex) {
        //     for (let i = 0; i < this.splitOrigText.length; i++) {
        //         if (this.splitNewText[i] !== this.splitOrigText[i]) {
        //             //something has changed
        //
        //             //
        //             let checkIdx = i + 1;
        //             return this.splitNewText[checkIdx] !== this.splitOrigText[i]
        //         }
        //     }
        //     return false;
        // }
    }

}
</script>

<style scoped>
.struck {
    text-decoration: line-through;
}


</style>
