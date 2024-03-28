import ParameterBag from '@/models/ParameterBag';
import {useHostsStore} from '@/stores/hosts';
import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useSearchStore = defineStore('search', () => {
    const query      = ref('');
    const perPage    = ref('100');
    const sort       = ref('desc');
    const files      = ref([] as string[]);
    const hostsStore = useHostsStore();

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

    function toQueryString(): string {
        const bag = new ParameterBag();
        bag.set('file', files.value.join(','), '');
        bag.set('query', query.value, '');
        bag.set('per_page', perPage.value, '100');
        bag.set('sort', sort.value, 'desc');
        bag.set('host', hostsStore.selected, 'localhost');
        return bag.toString();
    }

    return {files, query, perPage, sort, toggleFile, setFile, toQueryString}
});
