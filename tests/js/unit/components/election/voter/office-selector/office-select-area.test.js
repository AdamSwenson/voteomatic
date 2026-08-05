import OfficeSelectArea from '../../../../../../../resources/js/components/election/voter/office-selector/office-select-area.vue';

describe('office-select-area ballot progress', () => {
    const motions = [{id: 1, type: 'office'}, {id: 2, type: 'proposition'}, {id: 3, type: 'office'}];

    it('counts completed contests across offices and propositions', () => {
        const context = {
            motions,
            $store: {getters: {hasVotedOnMotion: (motion) => motion.id !== 2}}
        };

        expect(OfficeSelectArea.computed.completedCount.call(context)).toBe(2);
        expect(OfficeSelectArea.computed.completionPercentage.call({...context, completedCount: 2})).toBe(67);
    });

    it('has a safe zero-contest progress state', () => {
        expect(OfficeSelectArea.computed.completionPercentage.call({motions: [], completedCount: 0})).toBe(0);
    });
});
