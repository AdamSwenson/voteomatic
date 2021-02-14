/**
 * Usually used when handed a parameter that could be either an object
 * with an id property or the id itself.  This returns
 * the integer value.
 * @param ObjectOrId
 * @returns {Number|*}
 */
const idify = (ObjectOrId) =>{
    if (ObjectOrId instanceof Number) return ObjectOrId;

    return ObjectOrId.id;
}



export  {
    idify
};
