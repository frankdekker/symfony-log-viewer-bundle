import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useHostsStore = defineStore('hosts', () => {
    const hosts    = ref<{ [key: string]: string }>(JSON.parse(document.head.querySelector<HTMLMetaElement>('[name=hosts]')?.content ?? '[]'));
    const selected = ref(Object.keys(hosts.value)[0] ?? 'localhost');

    return {hosts, selected}
});
