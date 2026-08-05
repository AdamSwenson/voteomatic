<template>
    <section class="card vote-page" aria-labelledby="ballot-heading">
        <header class="card-header vote-page-header">
            <div>
                <p class="vote-page-eyebrow">Current ballot</p>
                <h1 id="ballot-heading" class="card-title">{{ cardTitle }}</h1>
            </div>
            <span class="vote-status" :class="statusClass" role="status">{{ statusLabel }}</span>
        </header>

        <div class="card-body vote-page-body" v-if="isReady">
            <div class="motion-panel">
                <h2 class="motion-panel-title">Motion before the body</h2>
                <motion-text-display :motion="motion" :motion-style="motionStyle"></motion-text-display>
            </div>
            <div class="ballot-details">
                <required-vote :motion="motion"></required-vote>
                <div class="ballot-badges" aria-label="Motion details">
                    <motion-type-badge :motion="motion"></motion-type-badge>
                    <required-vote-badge :motion="motion"></required-vote-badge>
                    <debatable-badge :motion="motion"></debatable-badge>
                </div>
            </div>
            <p class="motion-description" v-if="motionDescription">{{ motionDescription }}</p>
            <vote-receipt :receipt="receipt" v-if="hasVoted && receipt"></vote-receipt>
            <div class="vote-state-message" v-else-if="!isVotingAllowed && !isComplete" role="status"><strong>Voting is not open yet.</strong> The Chair will open this ballot when the motion is ready.</div>
            <div class="vote-state-message vote-state-message--closed" v-else-if="!isVotingAllowed && isComplete" role="status"><strong>Voting has closed.</strong> Results are available in the Results tab when released.</div>
        </div>

        <footer class="card-footer vote-actions" v-if="isVotingAllowed && !hasVoted">
            <p class="vote-actions-heading">Choose one response</p>
            <p class="vote-actions-help">Your selection cannot be changed after it is recorded.</p>
            <vote-buttons v-if="showButtons && isReady" :motion="motion" v-on:yay-clicked="handleYay" v-on:nay-clicked="handleNay"></vote-buttons>
        </footer>
    </section>
</template>

<script>
import VoteButtons from "../vote-casting/vote-buttons";
import RequiredVote from "../text-display/required-vote";
import VoteReceipt from "../text-display/vote-receipt";
import motionMixin from '../../mixins/motionStoreMixin';
import receiptMixin from "../../mixins/receiptMixin";
import motionObjectMixin from "../../mixins/motionObjectMixin";
import RequiredVoteBadge from "../motions/badges/required-vote-badge";
import DebatableBadge from "../motions/badges/debatable-badge";
import MotionTypeBadge from "../motions/badges/motion-type-badge";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import MotionTextDisplay from "../motions/text-display/motion-text-display";

export default {
    name: "vote-page",
    components: {MotionTextDisplay, MotionTypeBadge, DebatableBadge, RequiredVoteBadge, VoteReceipt, RequiredVote, VoteButtons},
    mixins: [motionMixin, motionObjectMixin, receiptMixin],
    data: function () { return {voteRecorded: false, titleText: {unVoted: 'Please vote on this motion', voted: 'Thank you for voting'}}; },
    computed: {
        motionStyle: function () { if (!this.isResolution) return 'motion-text'; },
        cardTitle: function () { if (!this.isVotingAllowed) return 'The current motion'; return this.hasVoted ? this.titleText.voted : this.titleText.unVoted; },
        votedUponMotionIds: function () { return this.$store.getters.getMotionIdsUserVotedUpon; },
        hasVoted: function () { return this.isReady ? this.votedUponMotionIds.includes(this.motion.id) : false; },
        isReady: function () { return !_.isUndefined(this.motion) && !_.isNull(this.motion) && !_.isUndefined(this.votedUponMotionIds) && !_.isNull(this.votedUponMotionIds); },
        isVotingAllowed: function () { return this.isReady && this.motion.isVotingAllowed; },
        motionDescription: function () { return this.isReady ? this.motion.description : ''; },
        showButtons: function () { return isReadyToRock(this.hasVoted) && !this.hasVoted; },
        statusLabel: function () { if (!this.isReady) return 'Loading ballot'; if (this.hasVoted) return 'Vote recorded'; if (this.isVotingAllowed) return 'Voting open'; if (this.isComplete) return 'Voting closed'; return 'Awaiting Chair'; },
        statusClass: function () { if (this.hasVoted) return 'vote-status--complete'; if (this.isVotingAllowed) return 'vote-status--open'; if (this.isComplete) return 'vote-status--closed'; return 'vote-status--waiting'; }
    },
    methods: { handleNay: function () { this.voteRecorded = true; }, handleYay: function () { this.voteRecorded = true; } }
}
</script>

<style scoped>
.vote-page { max-width: 960px; margin: 1.5rem auto; overflow: hidden; border: 1px solid #cbd5e1; box-shadow: 0 .5rem 1.5rem rgba(15,23,42,.08); }
.vote-page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.25rem 1.5rem; background: #f8fafc; }.vote-page-eyebrow { margin: 0 0 .25rem; color: #475569; font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }.vote-page-header h1 { margin: 0; font-size: clamp(1.35rem, 3vw, 2rem); }
.vote-status { flex: none; border-radius: 999px; padding: .4rem .7rem; font-size: .875rem; font-weight: 700; white-space: nowrap; }.vote-status--open { background: #dcfce7; color: #166534; }.vote-status--complete { background: #dbeafe; color: #1e40af; }.vote-status--waiting { background: #fef3c7; color: #92400e; }.vote-status--closed { background: #e2e8f0; color: #334155; }
.vote-page-body { padding: 1.5rem; }.motion-panel { border: 1px solid #cbd5e1; border-left: .35rem solid #1d4ed8; border-radius: .4rem; padding: 1.25rem; }.motion-panel-title { margin: 0 0 .75rem; color: #334155; font-size: .95rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
.motion-panel :deep(.motion-text) { font-size: clamp(1.25rem, 2.5vw, 1.75rem); line-height: 1.45; }
.ballot-details { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .75rem; margin-top: 1rem; color: #334155; }.ballot-details :deep(.required-vote p) { margin: 0; }.ballot-badges { display: flex; flex-wrap: wrap; gap: .45rem; }.ballot-badges :deep(.badge) { padding: .45rem .65rem; }.motion-description { margin: 1rem 0 0; color: #475569; }
.vote-state-message { margin-top: 1.25rem; border-left: .3rem solid #d97706; background: #fffbeb; color: #78350f; padding: 1rem; }.vote-state-message--closed { border-left-color: #64748b; background: #f1f5f9; color: #334155; }.vote-actions { border-top: 1px solid #cbd5e1; padding: 1.25rem 1.5rem 1.5rem; background: #f8fafc; }.vote-actions-heading { margin: 0; color: #0f172a; font-weight: 700; }.vote-actions-help { margin: .2rem 0 1rem; color: #475569; }
@media (max-width: 575.98px) { .vote-page { margin: 0; border-right: 0; border-left: 0; border-radius: 0; }.vote-page-header,.vote-page-body,.vote-actions { padding: 1rem; }.vote-status { font-size: .75rem; }.motion-panel { padding: 1rem; } }
</style>
