let _ = require('lodash');
// const { Settings } = require("../../../resources/js/models/Settings");
import Settings from "../../../resources/js/models/Settings";

describe("Settings", () => {
//obj from server
    let settingStore = {
        id: 2,
        settings: {
            a: 1,
            b: 3,
            c: 4,
            d: true,
            e: false
        }
    };
    let s;

    beforeEach(() => {
         s = new Settings(settingStore);

    });

    test('Dynamic getters', () => {

        expect(s.id).toEqual(settingStore.id);

        expect(s.a).toEqual(settingStore.settings.a);

        expect(s.b).toEqual(settingStore.settings.b);

        expect(s.c).toEqual(settingStore.settings.c);
    });

    test('Dynamic setters', () => {
        s.a = 0;
        s.b = 0;
        s.c = 0;

        expect(s.id).toEqual(settingStore.id);

        expect(s.a).toEqual(0);

        expect(s.b).toEqual(0);

        expect(s.c).toEqual(0);
    });


    test("isSettingTrue", () => {
    expect(s.isSettingTrue('d')).toBe(true);

        expect(s.isSettingTrue('e')).toBe(false);


        //undefined case
        expect(s.isSettingTrue('f')).toBe(false);
    });


    test("isAnySettingTrue", () => {
        expect(s.isAnySettingTrue(['d', 'e', 'f'])).toBe(true);

        expect(s.isAnySettingTrue(['e', 'f'])).toBe(false);


        //undefined case
        expect(s.isSettingTrue(['f'])).toBe(false);

});
});
