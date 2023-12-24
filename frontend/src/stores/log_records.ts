import type LogRecords from '@/models/LogRecords';
import axios from 'axios';
import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useLogRecordStore = defineStore('log_records', () => {
    const defaultData: LogRecords = {
        file: '',
        levels: {choices: {}, selected: []},
        channels: {choices: {}, selected: []},
        logs: [],
        paginator: null
    };

    const loading = ref(false);
    const records = ref<LogRecords>(defaultData);

    async function fetch(file: string, levels: string[], channels: string[], direction: string, perPage: string, query: string, offset: number) {
        const params: {[key: string]: string} = {file, direction, per_page: perPage, query};
        if (levels.length !== Object.keys(records.value.levels.choices).length) {
            params.levels = levels.join(',');
        }
        if (channels.length !== Object.keys(records.value.channels.choices).length) {
            params.channels = channels.join(',');
        }
        if (offset > 0) {
            params.offset = offset.toString();
        }
        loading.value = true;
        try {
            const response = await axios.get<LogRecords>('/logs/api/logs', {params: params});
            records.value = response.data;
        } catch (e) {
            console.error(e);
            records.value = defaultData;
        } finally {
            loading.value = false;
        }
    }

    return {loading, records, fetch}
});
