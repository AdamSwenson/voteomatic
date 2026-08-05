import VoteCountAlert from '../../../../../../resources/js/components/main/chair/vote-count-alert.vue';
import {createStore} from 'vuex';
import {mount} from '@vue/test-utils';

describe('vote-count-alert', () => {
    const getters = (cast, members) => ({
        getCastVotesCount: cast,
        getMemberCount: members
    });

    it('makes the chair’s remaining-voter count explicit', () => {
        const context = {$store: {getters: getters(7, 12)}, votesCast: 7};

        expect(VoteCountAlert.computed.votesOutstanding.call(context)).toBe(5);
        expect(VoteCountAlert.computed.memberCount.call(context)).toBe(12);
    });

    it('never reports a negative remaining-voter count', () => {
        const context = {$store: {getters: getters(14, 12)}, votesCast: 14};

        expect(VoteCountAlert.computed.votesOutstanding.call(context)).toBe(0);
    });

    it('labels the open-voting state and exposes the count to assistive technology', () => {
        const store = createStore({
            getters: {
                getIsAdmin: () => true,
                getActiveMotion: () => ({isComplete: false, isVotingAllowed: true}),
                getCastVotesCount: () => 7,
                getMemberCount: () => 12
            }
        });
        const wrapper = mount(VoteCountAlert, {
            global: {
                plugins: [store],
                stubs: ['end-voting-button', 'end-voting-modal', 'abort-voting-button', 'abort-voting-modal']
            }
        });

        expect(wrapper.text()).toContain('Voting is open');
        expect(wrapper.text()).toContain('7 of 12 members have voted');
        expect(wrapper.get('[aria-live="polite"]').exists()).toBe(true);
    });
});
