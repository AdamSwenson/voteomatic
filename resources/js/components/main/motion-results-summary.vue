<template>
    <section class="motion-results-summary" aria-labelledby="vote-result-heading">
        <p class="text-uppercase small fw-bold mb-1">Final vote</p>
        <h2 id="vote-result-heading" class="h3 mb-3">The motion {{ outcome }}</h2>

        <div class="row row-cols-2 row-cols-md-4 g-2" aria-label="Vote totals">
            <div class="col">
                <div class="border rounded p-2 h-100">
                    <span class="d-block text-muted small">Yea</span>
                    <strong class="fs-4">{{ displayCount(yayCount) }}</strong>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2 h-100">
                    <span class="d-block text-muted small">Nay</span>
                    <strong class="fs-4">{{ displayCount(nayCount) }}</strong>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2 h-100">
                    <span class="d-block text-muted small">Cast</span>
                    <strong class="fs-4">{{ displayCount(totalVotes) }}</strong>
                </div>
            </div>
            <div class="col">
                <div class="border rounded p-2 h-100">
                    <span class="d-block text-muted small">Required</span>
                    <strong class="fs-6">{{ requiredVote }}</strong>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'motion-results-summary',
    props: {
        isPassed: {default: null},
        yayCount: {default: null},
        nayCount: {default: null},
        totalVotes: {default: null},
        requiredVote: {default: '—'}
    },
    computed: {
        outcome: function () {
            if (this.isPassed === null || _.isUndefined(this.isPassed)) return 'was recorded';
            return this.isPassed ? 'passed' : 'failed';
        }
    },
    methods: {
        displayCount: function (count) {
            return count === null || _.isUndefined(count) ? '—' : count;
        }
    }
}
</script>
