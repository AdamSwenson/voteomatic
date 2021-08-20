<template>

    <div class="receipt-list-area"
    v-if="showArea">
        <h3>{{meetingName}}</h3>
        <p>{{meetingDate}}</p>
        <div ul="list-group">

        <receipt-list-item
            v-for="vote in allVotes"
            :vote-object="vote"
            :key="vote.id"
        ></receipt-list-item>

        </div>
    </div>

</template>

<script>
import ReceiptListItem from "./receipt-list-item";
import MeetingMixin from '../../mixins/meetingMixin';
import {isReadyToRock} from "../../utilities/readiness.utilities";

export default {
    name: "receipt-list-area",
    components: {ReceiptListItem},
    props: [],

    mixins: [MeetingMixin],

    data: function () {
        return {}
    },

    asyncComputed: {

        allVotes: function () {
            return this.$store.getters.getUsersCastVotes;
        },


        showArea : function(){
            return isReadyToRock(this.allVotes) && this.allVotes.length >0 ;
        }



    },

    computed: {},

    methods: {



    }

}
</script>

<style scoped>

</style>
