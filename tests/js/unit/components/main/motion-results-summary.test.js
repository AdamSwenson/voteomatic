import {mount} from '@vue/test-utils';
import MotionResultsSummary from '../../../../../resources/js/components/main/motion-results-summary.vue';

describe('motion-results-summary', () => {
    it('presents the outcome, vote totals, and threshold together', () => {
        const wrapper = mount(MotionResultsSummary, {
            props: {isPassed: true, yayCount: 9, nayCount: 3, totalVotes: 12, requiredVote: 'majority'}
        });

        expect(wrapper.text()).toContain('The motion passed');
        expect(wrapper.text()).toContain('Yea');
        expect(wrapper.text()).toContain('Nay');
        expect(wrapper.text()).toContain('Cast');
        expect(wrapper.text()).toContain('Required');
        expect(wrapper.text()).toContain('majority');
    });

    it('does not present missing totals as zero', () => {
        const wrapper = mount(MotionResultsSummary);

        expect(wrapper.text()).toContain('was recorded');
        expect(wrapper.text()).toContain('—');
    });
});
