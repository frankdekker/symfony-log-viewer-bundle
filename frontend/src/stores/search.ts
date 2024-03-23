import ParameterBag from '@/models/ParameterBag';
import {useHostsStore} from '@/stores/hosts';
import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useSearchStore = defineStore('search', () => {
    const query      = ref('');
    const perPage    = ref('100');
    const sort       = ref('desc');
    const hostsStore = useHostsStore();

    function toQueryString(params: { [key: string]: string } = {}): string {
        const bag = new ParameterBag(params);
        bag.set('query', query.value, '');
        bag.set('per_page', perPage.value, '100');
        bag.set('sort', sort.value, 'desc');
        bag.set('host', hostsStore.selected, 'localhost');
        return bag.toString();
    }

    return {query, perPage, sort, toQueryString}
});
