import {faker} from '@faker-js/faker';

import Election from "../../resources/js/models/Election";
import Office from "../../resources/js/models/Office";


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
