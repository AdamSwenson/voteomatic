import {faker} from '@faker-js/faker';

import Election from "../../resources/js/models/Election";
import Office from "../../resources/js/models/Office";
import Motion from "../../resources/js/models/Motion";


export function electionFactory() {
    return new Election({id: 3});
}


export function officeFactory() {
    return new Office({
        id: faker.number.int(),
        content: faker.lorem.sentence(),
        description: faker.lorem.sentence(),
        max_winners: faker.number.int(),
    });
}

/**
 * Creates a non-election motion
 * @returns {Motion}
 */
export function motionFactory() {
    return new Motion({
        id: faker.number.int(),
        content: faker.lorem.sentence(),
        description: faker.lorem.sentence(),
        requires: faker.number.float({min:0.1, max:1}),
        type: faker.helpers.arrayElement(['main', 'amendment']),
        info: faker.lorem.sentence(),
        is_complete: faker.helpers.arrayElement([true, false]),
        is_voting_allowed: faker.helpers.arrayElement([true, false]),
        is_resolution: faker.helpers.arrayElement([true, false]),
        applies_to: faker.helpers.arrayElement([null, faker.number.int()]),
        seconded : faker.helpers.arrayElement([true, false]),
        superseded_by: faker.helpers.arrayElement([null, faker.number.int()]),
        debatable: faker.helpers.arrayElement([true, false])
    });
}
