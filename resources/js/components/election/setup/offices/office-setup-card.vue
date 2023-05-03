<template>
    <div class="office-setup card router-tab-touching-card">
        <div class="row  mt-2">

            <div class="col-lg-3">
                <office-list-card></office-list-card>
            </div>

            <div class="col-lg-9">
                <office-edit-card v-if="showOfficeSetup"></office-edit-card>

                <div class="card-body" v-else>
                    <p class="card-text">Please select an office to edit or
                    create a new one.</p>
                </div>

                <div class="card pool-management mt-2" v-if="showOfficeSetup">

                    <div class="card-header">
                        <h4 class="card-subtitle">Manage candidates for this office</h4>
                    </div>

                    <div class="card-body" v-if="showOfficeSetup">
                        <p class="card-text">The list on the left contains people who are eligible to be
                            nominated for this office.</p>

                        <p class="card-text">The list on the right contains those who have been nominated
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

                </div>
            </div>
        </div>

    </div>
</template>

<script>
import CandidatePoolCard from "../pool/candidate-pool-card";
import CurrentCandidatesCard from "../candidates/current-candidates-card";
import OfficeEditCard from "./edit/office-edit-card";
import OfficeListCard from "./office-list-card";
import CreateOfficeButton from "./create/create-office-button";
import MeetingMixin from "../../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../../utilities/readiness.utilities";
import DeleteOfficeModal from "./delete/delete-office-modal";
import DeleteOfficeButton from "./delete/delete-office-button";
import ImportPoolControls from "../pool/import/import-pool-controls";
import ImportOfficesControls from "./import/import-offices-controls";

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
            return isReadyToRock(this.motion) && this.motion.type === 'election';
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
