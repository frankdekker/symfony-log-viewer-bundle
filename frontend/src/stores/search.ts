import ParameterBag from '@/models/ParameterBag';
import {useHostsStore} from '@/stores/hosts';
import {useFolderStore} from '@/stores/folders';
import {defineStore} from 'pinia'
import {computed, ref} from 'vue'

export const useSearchStore = defineStore('search', () => {
    const query       = ref('');
    const between     = ref('');
    const perPage     = ref('100');
    const sort        = ref('desc');
    const files       = ref([] as string[]);
    const hostsStore  = useHostsStore();
    const folderStore = useFolderStore();

    const hasCompressedFileSelected = computed(() => {
        const allFiles = folderStore.folders.flatMap(folder => folder.files)
        const selectedFiles = allFiles.filter(file => files.value.includes(file.identifier));
        return selectedFiles.some(file => file.is_compressed);
    });

    function addFile(identifier: string) {
        if (files.value.includes(identifier) === false) {
            files.value.push(identifier);
        }
    }

    function toggleFile(identifier: string) {
        if (files.value.includes(identifier)) {
            files.value = files.value.filter(file => file !== identifier);
            return;
        }
        files.value.push(identifier);
    }

    function setFile(identifier: string) {
        files.value.splice(0, files.value.length, identifier);
    }

    function removeFile(identifier: string) {
        files.value = files.value.filter(file => file !== identifier);
    }

    function toQueryString(): string {
        const bag = new ParameterBag();
        bag.set('file', files.value.join(','), '');
        bag.set('query', query.value, '');
        bag.set('between', between.value, '');
        bag.set('per_page', perPage.value, '100');
        bag.set('sort', sort.value, 'desc');
        bag.set('host', hostsStore.selected, 'localhost');
        return bag.toString();
    }

    return {files, query, between, perPage, sort, hasCompressedFileSelected, addFile, toggleFile, setFile, removeFile, toQueryString}
});
