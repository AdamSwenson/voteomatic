let _ = require('lodash');


/**
 * Usually used when handed a parameter that could be either an object
 * with an id property or the id itself.  This returns
 * the integer value.
 * @param ObjectOrId
 * @returns {Number|*}
 */
module.exports.idify = (ObjectOrId) =>{
// const idify = (ObjectOrId) =>{
    if(_.isNumber(ObjectOrId)) return ObjectOrId;
    // if (ObjectOrId instanceof Number) return ObjectOrId;

    return ObjectOrId.id;
}


/**
 * Returns the object stored in the array which
 * has the provided id
 *
 * @param storageArray
 * @param id
 * @returns {*}
 */
module.exports.getById = (storageArray, id) =>{
    // return function ( state, id ) {
    let r = storageArray.filter(function (i) {
        if (i.id === id) {
            return i;
        }
    });
    return r[0];
}


// export  {
//     idify, getById
// };


