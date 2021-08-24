<script>
import MeetingMixin from '../../mixins/meetingMixin';
import AllReceiptsMixin from '../../mixins/allReceiptsMixin';
import ButtonParent from "../parents/button-parent";

export default {
    name: "download-receipts-button",
    extends: ButtonParent,
    props: [],

    mixins: [MeetingMixin, AllReceiptsMixin],

    data: function () {
        return {
            label : 'Download all receipts',
            styling: ' btn-primary '
        }
    },

    asyncComputed: {
        filename: function () {
            return `${this.meetingDate} ${this.meetingName} vote receipts.txt`;

        },
    },

    computed: {},

    methods: {
handleClick: function(){
    this.downloadFile();
},
        downloadFile: function () {
            let element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(this.text));
            element.setAttribute('download', this.filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();
            document.body.removeChild(element);

            // download("hello.txt","This is the content of my file :)");

        }


    }


}
</script>

<style scoped>

</style>
