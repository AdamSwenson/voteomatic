import {createStore} from 'vuex';
import {mount} from '@vue/test-utils';

import VoteConfirmationModal from "../../../resources/js/components/vote-casting/vote-confirmation-modal.vue";
import Motion from "../../../resources/js/models/Motion";
import Vote from "../../../resources/js/models/Vote";

const motion = new Motion({id: 4});

function mountModal(type) {
    const castMotionVote = jest.fn(() => Promise.resolve({receipt: 'saved-receipt'}));
    const store = createStore({
        actions: {castMotionVote},
        getters: {getActiveMotion: () => motion}
    });

    return {
        castMotionVote,
        wrapper: mount(VoteConfirmationModal, {
            props: {type},
            global: {plugins: [store]}
        })
    };
}

describe.each([
    ['yay', 'Aye', true, 'btn-success', 'vote-confirmation--aye'],
    ['nay', 'Nay', false, 'btn-danger', 'vote-confirmation--nay']
])('vote confirmation for %s', (type, label, isYay, buttonClass, modalClass) => {
    test('uses an explicit, color-matched irreversible confirmation', () => {
        const {wrapper} = mountModal(type);

        expect(wrapper.get('.modal-title').text()).toBe(`Confirm your ${label} vote`);
        expect(wrapper.get('button.yes').text()).toBe(`Record ${label} vote`);
        expect(wrapper.get('button.yes').classes()).toContain(buttonClass);
        expect(wrapper.get('.modal-content').classes()).toContain(modalClass);
        expect(wrapper.text()).toContain('cannot be changed');
        expect(wrapper.get('.modal').attributes('aria-labelledby')).toBe(`${type}ConfirmationModalLabel`);
    });

    test('records the selected vote and emits the matching event', async () => {
        const {wrapper, castMotionVote} = mountModal(type);

        await wrapper.get('button.yes').trigger('click');

        expect(castMotionVote).toHaveBeenCalledTimes(1);
        const vote = castMotionVote.mock.calls[0][1];
        expect(vote).toBeInstanceOf(Vote);
        expect(vote.isYay).toBe(isYay);
        expect(vote.motionId).toBe(motion.id);
        expect(wrapper.emitted(`${type}-clicked`)).toHaveLength(1);
    });

    test('does not record a vote when dismissed', async () => {
        const {wrapper, castMotionVote} = mountModal(type);

        await wrapper.get('button.no').trigger('click');

        expect(castMotionVote).not.toHaveBeenCalled();
    });
});
