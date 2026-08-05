import fs from 'fs';
import path from 'path';

describe('keyboard focus styles', () => {
    const stylesheet = fs.readFileSync(path.resolve(__dirname, '../../../../resources/sass/_layoutvars.scss'), 'utf8');

    it('provides a visible, offset focus indicator without showing it for pointer-only interactions', () => {
        expect(stylesheet).toMatch(/:focus-visible\s*\{/);
        expect(stylesheet).toContain('outline: 3px solid #ffc107');
        expect(stylesheet).toContain('outline-offset: 3px');
    });
});
