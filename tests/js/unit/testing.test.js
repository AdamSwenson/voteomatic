
import { createStore } from 'vuex';
import { mount } from '@vue/test-utils';//'@vue/test-utils'


const App = {
    computed : {}
};
let store = createStore({
// let store = new Vuex.Store({
//     actions, getters
});

const wrapper = mount(App, {
    global: {
        plugins: [store]
    }
})





function sum(a, b) {
    return a + b;
}

test('adds 1 + 2 to equal 3', () => {
    expect(sum(1, 2)).toBe(3);
});
