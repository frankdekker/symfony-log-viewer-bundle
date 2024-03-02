import {useHostsStore} from '@/stores/hosts';
import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useSearchStore = defineStore('search', () => {
    const query   = ref('');
    const perPage = ref('50');
    const sort    = ref('desc');
    const hostsStore = useHostsStore();

    function toQueryString(params: { [key: string]: string } = {}): string {
        if (query.value !== '') {
            params.query = query.value;
        }

        if (perPage.value !== '50') {
            params.perPage = perPage.value;
        }

        if (sort.value !== 'desc') {
            params.sort = sort.value;
        }

        const host = hostsStore.selected;
        if (host !== 'localhost' && host !== '') {
            params.host = host;
        }

        return new URLSearchParams(params).toString();
    }

    return {query, perPage, sort, toQueryString}
});
