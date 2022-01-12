let _ = require('lodash');
// const {PoolMember} = require("../../../resources/js/models/PoolMember");

import PoolMember from "../../../resources/js/models/PoolMember";

test('get candidate info fields', () => {

    let o = {
        id : 1,
        info: {
            field1: 'taco',
            field2: 'dog'
        }
    };

    let obj = new PoolMember(o);

    expect(obj.getInfoField('field1')).toBe(o.info.field1);
    expect(obj.getInfoField('field2')).toBe(o.info.field2);
    expect(obj.getInfoField('field3')).toBe('');

});


test('set candidate info fields', () => {

    let o = {
        id : 1,
        info: {
            field1: 'taco',
            field2: 'dog'
        }
    };

    let obj = new PoolMember(o);

    expect(obj.getInfoField('field1')).toBe(o.info.field1);

    //call
    let newVal ='cat';
    obj.setInfoField('field1', newVal);
    expect(obj.info.field1).toBe(newVal);

});
