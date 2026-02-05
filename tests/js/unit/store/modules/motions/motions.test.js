import Vuex from 'vuex'
// import actions from "../../../../../../resources/js/store/modules/motions/motions.actions.crud";
import m from "../../../../../../resources/js/store/modules/motions/motions";
// import getters from "../../../../../../resources/js/store/modules/motions/motions";
// import state from "../../../../../../resources/js/store/modules/motions/motions";

import {faker} from '@faker-js/faker';

import sinon from 'sinon';
import {motionFactory} from "../../../../modelFactories";

let state = {
    motions: []
}

describe('motions', () => {
    let motions;
    let numMotions = 5;

    describe('mutations', () => {
        beforeEach(() => {
            motions = [];
            for (var i = 0; i < numMotions; i++) {
                motions.push(motionFactory())
            }
        });

        describe('addMotionToStore', () => {
            beforeEach(() => {
                state.motions = motions;
            });

            test('motionNotPresent', () => {
                let motion = motionFactory();

                //call
                m.mutations.addMotionToStore(state, motion);

                //check
                expect(state.motions.length).toBe(numMotions + 1);
                expect(state.motions.indexOf(motion)).toBeGreaterThan(-1);
            });

            test('motionAlreadyPresent', () => {
                let motion = faker.helpers.arrayElement(motions);

                //call
                m.mutations.addMotionToStore(state, motion);

                //check
                expect(state.motions.length).toBe(numMotions );
                expect(state.motions.indexOf(motion)).toBeGreaterThan(-1);
            });

        });

        describe('addMotionToStore', () => {

            test('deleteMotion ', () => {
                //prep
                state.motions = motions;
                let newContent = faker.lorem.paragraph();
                let motion = faker.helpers.arrayElement(motions);
                let originalContent = motion.content;
                expect(motion.content).not.toEqual(newContent);
                expect(state.motions.length).toBe(numMotions);
                expect(state.motions.indexOf(motion)).toBeGreaterThan(-1);

                //call
                m.mutations.deleteMotion(state, motion);

                //check
                expect(state.motions.indexOf(motion)).toBe(-1);
                expect(state.motions.length).toEqual(numMotions - 1);

            });

        });
    });


});
