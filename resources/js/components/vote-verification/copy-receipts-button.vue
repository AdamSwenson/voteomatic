<script>
import ButtonParent from "../parents/button-parent";
import AllReceiptsMixin from '../../mixins/allReceiptsMixin';
import MeetingMixin from '../../mixins/meetingMixin';

export default {
    name: "copy-receipts-button",
    components: {},
    props: [],
    extends: ButtonParent,

    mixins: [AllReceiptsMixin, MeetingMixin],

    data: function () {
        return {
            label: 'Copy all receipts to clipboard',
            styling: ' btn-primary '
        }
    },

    asyncComputed: {


    },

    computed: {},

    methods: {

        handleClick : function(){
          this.copyToClipboard();
        },


        copyToClipboard: function () {
            // if (!navigator.clipboard) {
            //     //fallbackCopyTextToClipboard(text);
            //     return;
            // }
            navigator.clipboard.writeText(this.text).then(function () {
                console.log('Async: Copying to clipboard was successful!');
            }, function (err) {
                console.error('Async: Could not copy text: ', err);
            });
            // function fallbackCopyTextToClipboard(text) {
            //     var textArea = document.createElement("textarea");
            //     textArea.value = text;
            //
            //     // Avoid scrolling to bottom
            //     textArea.style.top = "0";
            //     textArea.style.left = "0";
            //     textArea.style.position = "fixed";
            //
            //     document.body.appendChild(textArea);
            //     textArea.focus();
            //     textArea.select();
            //
            //     try {
            //         var successful = document.execCommand('copy');
            //         var msg = successful ? 'successful' : 'unsuccessful';
            //         console.log('Fallback: Copying text command was ' + msg);
            //     } catch (err) {
            //         console.error('Fallback: Oops, unable to copy', err);
            //     }
            //
            //     document.body.removeChild(textArea);
            // }

        }


    }

}
</script>

<style scoped>

</style>
