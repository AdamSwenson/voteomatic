<!--<template>-->
<!--    <span class="insert-failed " v-bind:class="iconStyling">-->
<!--    <a-->
<!--        tabindex="0"-->
<!--        data-bs-toggle="popover"-->
<!--        data-bs-trigger="focus"-->
<!--        v-bind:data-bs-content="popoverContent"-->
<!--    >-->
<!--        <i class="bi " v-bind:class="icon"></i>-->
<!--    </a>-->
<!--        <span class="visually-hidden" v-bind:id="textSlotId"><slot></slot></span>-->
<!--        </span>-->


<!--</template>-->

<script>

import {isReadyToRock} from "../../../utilities/readiness.utilities";
import TextStylerParentPopover from "./text-styler-parent-popover";
/**
 * Indicates that there was an attempt to insert text at this point.
 */

export default {
    name: "insert-failed",
extends : TextStylerParentPopover,

    // components: {TextStylerParentPopover},
    props: [
        // 'text',
        'amendmentId'],

    mixins: [],

    data: function () {
        return {
            icon: 'bi-pencil',
            textStyling: "text-danger ",
            iconStyling: "text-danger ",
        tt: '  '
        }
    },

    // watch: {
    //   amendmentId : function(){
    //       // var element = document.getElementById(this.textSlotId);
    //       // window.console.log(element, this.textSlotId);
    //       // this.tt = element.innerHTML;
    //   }
    // },
    //
    // asyncComputed: {
    //     textSlotId: function () {
    //         return 'insertFailed' + _.toString(this.amendmentId);
    //         // return this.name + _.toString(this.amendmentId);
    //     },
    //
    //     text : function(){
    //         return this.tt;
    //         // var element = document.getElementById(this.textSlotId);
    //         // if(! isReadyToRock(element)) return '';
    //         // return element.innerHTML;
    //
    //     },
    //
    //     popoverContent: function () {
    //         // return 'dog';
    //         return this.tt;
    //         // var element = document.getElementById(this.textSlotId);
    //         // if(! isReadyToRock(element)) return '';
    //         // return element.innerHTML;
    //         // return element.innerText;
    //         // element.parentNode.removeChild(element);
    //         // return this.text;
    //     }
    // }
    // ,
    //
    // computed: {}
    // ,
    //
    // methods: {}
    // ,
    //
    // mounted() {
    //     let me = this;
    //     this.$nextTick(function () {
    //         setTimeout(() => {
    //         var element = document.getElementById(me.textSlotId);
    //         window.console.log('ll', element.innerHTML, me.textSlotId);
    //             me.tt = element.innerHTML;
    //             // element.parentNode.removeChild(element);
    //         }, 5000)
    //     });
    //     // let pid = document.getElementById(this.itemId);
    //     // window.console.log(pid);
    //     // return new bootstrap.Popover(pid);
    //     //
    //     // var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    //     //
    //     // window.console.log('pop', popoverTriggerList);
    //     // var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    //     //     return new bootstrap.Popover(popoverTriggerEl)
    //     // })
    //     // this.$nextTick(function () {
    //     //     var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    //     //
    //     //     var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    //     //         return new bootstrap.Popover(popoverTriggerEl)
    //     //     })
    //     // });
    // }

}
</script>

<style scoped>

</style>
