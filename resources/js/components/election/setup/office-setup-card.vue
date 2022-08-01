<template>
    <div class="card office-setup">
        <div class="row">

            <div class="col-lg-3">
                <office-list-card></office-list-card>
            </div>
            <div class="col-lg-9">
                <!--        <div class="card-body" v-if="showOfficeSetup">-->
                <office-edit-card v-if="showOfficeSetup"></office-edit-card>
                <!--        </div>-->

                <div class="card pool-management mt-2" v-if="showOfficeSetup">

                    <div class="card-header">
                        <h5 class="card-subtitle">Manage candidates for this office</h5>
                    </div>

                    <div class="card-body" v-if="showOfficeSetup">
                        <p class="card-text">The list on the left contains people who are eligible to be
                            nominated for this office. The list on the right contains those who have been nominated
                            --i.e.,
                            <u>candidates</u> whom voters will select.</p>
                        <p class="card-text">Use the Nominate buttons to add names
                            from the pool on the left to the list of candidates
                            on the right.</p>

                        <div class="row">
                            <div class="col-md-6">
                                <candidate-pool-card></candidate-pool-card>
                            </div>

                            <div class="col-md-6">
                                <current-candidates-card></current-candidates-card>
                            </div>

                        </div>

                    </div>

<!--                    <div class="card-footer" v-if="showOfficeSetup">-->
<!--                        <div class="row ">-->
<!--                            <div class="col-lg-3">-->
<!--                                <import-pool-controls></import-pool-controls>-->
<!--                            </div>-->
<!--                            <div class="col-lg-3">-->
<!--&lt;!&ndash;                                <import-offices-controls></import-offices-controls>&ndash;&gt;-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
        <!--        <div class="card-footer" v-if="showOfficeSetup">-->
        <!--            <div class="text-end">-->
        <!--                <delete-office-button></delete-office-button>-->
        <!--                <delete-office-modal></delete-office-modal>-->
        <!--            </div>-->

        <!--        </div>-->


    </div>
</template>

<script>
import CandidatePoolCard from "./candidate-pool-card";
import CurrentCandidatesCard from "./current-candidates-card";
import OfficeEditCard from "./office-edit-card";
import OfficeListCard from "./office-list-card";
import CreateOfficeButton from "./controls/create-office-button";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import DeleteOfficeModal from "./controls/delete-office-modal";
import DeleteOfficeButton from "./controls/delete-office-button";
import ImportPoolControls from "./pool/import/import-pool-controls";
import ImportOfficesControls from "./import-offices/import-offices-controls";

// import bsCustomFileInput from 'bs-custom-file-input'

export default {
    name: "office-setup-card",
    components: {
        ImportOfficesControls,
        ImportPoolControls,
        DeleteOfficeButton,
        DeleteOfficeModal,
        CreateOfficeButton, OfficeListCard, OfficeEditCard, CurrentCandidatesCard, CandidatePoolCard
    },
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        /**
         * Hide the editing fields if no office is selected since
         * otherwise that seems confusing
         * @returns {boolean}
         */
        showOfficeSetup: function () {
            return isReadyToRock(this.motion);
        }
    },

    computed: {},

    methods: {},

    mounted() {
        // dev This would be used if decide to use bs-custom-file-input for the file field

        // $(document).ready(function () {
        //     bsCustomFileInput.init()
        // })
    }

}
</script>

<style scoped>

</style>
