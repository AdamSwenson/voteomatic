
// module.exports = {

    /**
     * Determines whether the thing has loaded.
     * If a property name is provided, it will also check that
     * it is defined and not null.
     *
     * The overly long name is due to there being lots of
     * isReady and similar laying around.
     *
     * @param thing
     * @param prop String name of the property to optionally check
     * @returns {boolean}
     */
    const isReadyToRock = (thing, prop=null) =>{
        if(!_.isNull(prop)){
            return ! _.isUndefined(thing) && ! _.isNull(thing)&& ! _.isUndefined(thing[prop]) && ! _.isNull(thing[prop]);
        }
        return ! _.isUndefined(thing) && ! _.isNull(thing);
    };

// }

export  {
    isReadyToRock
};
