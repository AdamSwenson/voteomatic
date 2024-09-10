<template>
      <span v-bind:class="iconStyling">
    <a
        tabindex="0"
        data-bs-toggle="popover"
        data-bs-trigger="focus"
        v-bind:data-bs-content="popoverContent"
    >
        <i class="bi " v-bind:class="icon"></i>
    </a>
        <span class="visually-hidden" v-bind:id="textSlotId"><slot></slot></span>
        </span>

    <!--    <span class="insert-failed text-danger">-->
    <!--    <a-->
    <!--        tabindex="0"-->
    <!--        data-bs-toggle="popover"-->
    <!--        data-bs-trigger="focus"-->
    <!--        v-bind:data-bs-content="popoverContent"-->
    <!--    >-->
    <!--      <i class="bi bi-pencil"></i>-->
    <!--    </a>-->
    <!--        </span>-->
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";

/**
 * Indicates that there was an attempt to insert text at this point.
 */
export default {
    // name: "text-styler-parent-popover",
    props: {
        // 'text',
        amendmentId: {
            default: ''
        }
    },

    mixins: [],

    data: function () {
        return {
            // stylerName,
            // icon: 'bi-pencil',
            // textStyling: "text-danger ",
            // iconStyling: "text-danger ",
            tt: '  '
        }
    },

    watch: {
        amendmentId(aid) {
            window.console.log('am', this.amendmentId);
            // var element = document.getElementById(this.textSlotId);
            // window.console.log(element, this.textSlotId);
            // this.tt = element.innerHTML;
        }
    },

    asyncComputed: {
        textSlotId: function () {
            // return 'insertFailed' + _.toString(this.amendmentId);
            return this.stylerName + _.toString(this.amendmentId);
        },

        tel: function () {
            // return this.tt;
            let e = document.getElementById(this.textSlotId);
            return isReadyToRock(e);
            if(! isReadyToRock(element)) return '';
            // return element.innerHTML;

        },

        popoverContent: function () {
            // return 'dog';
            return this.tt;
            // var element = document.getElementById(this.textSlotId);
            // if(! isReadyToRock(element)) return '';
            // return element.innerHTML;
            // return element.innerText;
            // element.parentNode.removeChild(element);
            // return this.text;
        }
    }
    ,

    computed: {}
    ,

    methods: {

        /**
         * Added to fix display in VOT-212
         * @param text
         * @returns {*}
         */
        filterSpace : function(text) {
            let r = new RegExp('&nbsp;', 'g')
            return text.replaceAll(r, '');
        },

        setPopoverContent: function () {
            var element = document.getElementById(this.textSlotId);
            if(isReadyToRock(element)){
                // window.console.log('ll', element.innerHTML, .textSlotId);
                this.tt = this.filterSpace(element.innerHTML);
            }

        }

    },

    mounted() {
        let me = this;
        this.$nextTick(function () {
            window.console.log('j', me.amendmentId, me.textSlotId);
            // me.setPopoverContent();
            setTimeout(() => {
                me.setPopoverContent();
                // var element = document.getElementById(me.textSlotId);
                // window.console.log('ll', element.innerHTML, me.textSlotId);
                // me.tt = element.innerHTML;
                // element.parentNode.removeChild(element);
            }, 5000)
        });

    },


}
</script>

<style scoped>

</style>
