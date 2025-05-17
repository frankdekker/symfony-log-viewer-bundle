import {defineStore} from 'pinia';
import {ref} from 'vue';

export const useSearchFilterStore = defineStore('search_filter', () => {
    const expanded = ref(false);

    function toggle() {
        expanded.value = !expanded.value;
    }

    return {expanded, toggle};
});
