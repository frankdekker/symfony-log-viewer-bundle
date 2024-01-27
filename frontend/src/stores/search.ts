import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useSearchStore = defineStore('search', () => {
    const query   = ref('');
    const perPage = ref('50');
    const sort    = ref('desc');

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

        return new URLSearchParams(params).toString();
    }

    return {query, perPage, sort, toQueryString}
});
