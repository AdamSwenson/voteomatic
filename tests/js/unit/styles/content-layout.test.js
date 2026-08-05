import fs from 'fs';
import path from 'path';

describe('content layout styles', () => {
    const appStyles = fs.readFileSync(path.resolve(__dirname, '../../../../resources/sass/app.scss'), 'utf8');
    const layoutStyles = fs.readFileSync(path.resolve(__dirname, '../../../../resources/sass/_layoutvars.scss'), 'utf8');

    it('keeps Bootstrap containers and the main app area clear of viewport edges', () => {
        expect(appStyles).toContain('$container-padding-x : 1rem');
        expect(layoutStyles).toMatch(/\.main-area\s*\{\s*padding-inline: 1rem/);
    });

    it('adds more breathing room on desktop layouts', () => {
        expect(layoutStyles).toContain('@media (min-width: 992px)');
        expect(layoutStyles).toMatch(/\.main-area\s*\{\s*padding-inline: 2rem/);
    });
});
