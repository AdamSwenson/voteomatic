
import IModel from "./IModel";


/**
 * This is a transportation class.
 * It is the common payload which all participating
 * mutations and actions receive
 */
export default class Payload extends IModel{

    /**
     * Returns a list of strings which are property
     * names. These fields can be filled from the input
     * @returns {[string,string]}
     */
    static get fillableProps() {
        return [
            // 'array',
            'callback',
            'id',
            'mutateSilently',
            // 'options',
            'object',
            // 'parent', 'parentNode',
            // 'type',
            'updateProp', 'updateVal',
            // 'updateValence',
        ];
    }

    constructor() {

        super();
        //the object's db id
        this._id;
        //the index value of the object
        // this._index;

        /** Whether to fail to notify subscribers of the mutation */
        this.mutateSilently = false;

        /** The object in the payload */
        this.object;
        //
        // /** The string in the payload */
        // this._str;
        //
        // /** The timestamp in the payload */
        // this._stamp;

        // this.options;
        // this.type;

        /** The name of the property to update */
        this.updateProp;
        /** The new value to set the property in updateProp */
        this.updateVal;

        this._successCallback;

    }
    //
    // get identifier() {
    //
    //     if ( this.obj instanceof Item ) return this.obj;
    //
    //     if ( this.serialNumber !== null ) return this.serialNumber;
    //
    //     if ( this.index !== null ) return this.index;
    // }
    //
    // get identifierType() {
    //
    //     if ( this.obj instanceof Item ) return 'obj';
    //
    //     if ( this.serialNumber !== null ) return 'serialNumber';
    //
    //     if ( this.index !== null ) return 'index';
    // }
    //
    //
    // getStoredObject ( store ) {
    //     switch ( this.identifierType ) {
    //         case 'obj':
    //             return this.obj;
    //             break;
    //         case 'serialNumber':
    //             return store.getters.getItemBySerialNumber( this.serialNumber);
    //             break;
    //         case 'index':
    //             return store.getters.getItemByIndex( this.index);
    //             break;
    //     }
    //
    // }


    get callback() {
        if ( typeof this._successCallback === 'undefined' ) {
            //dummy callable
            return () => {
                return true;
            }
        }
        return this._successCallback();
    }

    set callback( v ) {
        this._successCallback = v;
    }

    /*  ************************* Identifier values ************************* */
    // get id() {
    //     return this._id;
    // }

    // /**
    //  * Retrieve the index where it is possible
    //  * different fields could have different values.
    //  * This enforces the order of precedence between the fields
    //  */
    // static getIndex( payload ) {
    //     if ( this.checkIfPayload( payload ) ) {
    //         //If an object is set, that object's index
    //         //is always correct.
    //         if ( typeof payload.obj !== 'undefined' && typeof payload.obj.index !== 'undefined' ) {
    //             return payload.obj.index;
    //         } else {
    //             return payload.index;
    //         }
    //     }
    // }
    //

    //
    // get complexIndex(){
    //     if(typeof this._index2 != 'undefined'){
    //         return this._index
    //     }
    // }

    /*  ************************* Payload values ************************* */
    // get num() {
    //     return this._num;
    // }
    //
    // set num( v ) {
    //     //todo numeric check
    //     this._num = v;
    // }

//
//     get obj() {
//         return this._obj;
//     }
//
//     set obj( val ) {
//         if ( typeof val == 'object' ) {
//             this._obj = val;
//         }
// //todo error handling
//     }

    //
    //
    // get str() {
    //     return this._obj;
    // }
    //
    // set str( v ) {
    //     //todo string check
    //     this._str = v;
    // }
    //
    // get objNode() {
    //     return this._objNode
    // }
    //
    // set objNode( val ) {
    //     if ( !val instanceof Node ) throw new Error( "non-node attempting to be set as objNode in Payload" );
    //     this._objNode = val;
    // }
    //
    //
    // get parentNode() {
    //     return this._parentNode
    // }
    //
    // set parentNode( val ) {
    //     if ( !val instanceof Node ) throw new Error( "non-node attempting to be set as parentNode in Payload" );
    //     this._parentNode = val;
    // }

    static get aliasMap() {
        return {

        };

    }


    static factory( params ) {
        let p = new Payload();
        if ( typeof params !== 'undefined' ) {

            //fill any fillable values
            this.fillableProps.forEach( function ( v ) {

                if ( typeof params[ v ] !== 'undefined' ) {
                    p[ v ] = params[ v ];
                }
            } );
            //
            // //fill any aliased values
            // for (let v in this.aliasMap) {
            //     if ( typeof params[ v ] !== 'undefined' ) {
            //         // console.log( 'alias', v, map[v] );
            //         p[ this.aliasMap[ v ] ] = params[ v ];
            //     }
            // }
        }
        return p;
    }

    static checkIfPayload( payload ) {
        //received payload object case
        if ( payload instanceof Payload ) {
            return true;
        }
        return false;
        // throw new Exception( "Non Payload passed to a Payload requiring method" );
    }

}
