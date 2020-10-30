/**
 * Created by adam on 2020-07-13.
 */

module.exports = {


    setAdmin : (state, {updateProp, updateVal}) => {

        Vue.set(state, 'isAdmin', updateVal);
    }

}
