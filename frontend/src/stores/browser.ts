import {defineStore} from 'pinia';
import {ref} from 'vue';

export const useBrowserStore = defineStore('browser', () => {
    const autorefresh = ref(false);

    return {autorefresh};
});
