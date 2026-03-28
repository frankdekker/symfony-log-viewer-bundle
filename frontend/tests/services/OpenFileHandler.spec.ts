import {beforeEach, describe, expect, test, vi} from 'vitest';
import handleOpenFile from '../../src/services/OpenFileHandler';

const pushMock = vi.fn();

vi.mock('vue-router', () => ({
    useRoute: () => ({path: '/'}),
    useRouter: () => ({push: pushMock}),
}));

const mockFolders = vi.fn(() => []);
vi.mock('../../src/stores/folders.ts', () => ({
    useFolderStore: () => ({get folders() { return mockFolders(); }}),
}));

describe('OpenFileHandler', () => {
    beforeEach(() => {
        pushMock.mockReset();
    });

    test('redirects to log view for a regular file without sort parameter', () => {
        mockFolders.mockReturnValue([{files: [{identifier: 'abc123', open: true, is_compressed: false}]}]);
        handleOpenFile();
        expect(pushMock).toHaveBeenCalledWith('/log?file=abc123');
    });

    test('redirects with sort=asc when the auto-opened file is compressed', () => {
        mockFolders.mockReturnValue([{files: [{identifier: 'abc123', open: true, is_compressed: true}]}]);
        handleOpenFile();
        expect(pushMock).toHaveBeenCalledWith('/log?file=abc123&sort=asc');
    });

    test('does not redirect if no file has open set to true', () => {
        mockFolders.mockReturnValue([{files: [{identifier: 'abc123', open: false, is_compressed: false}]}]);
        handleOpenFile();
        expect(pushMock).not.toHaveBeenCalled();
    });
});
