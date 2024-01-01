import type LogRecords from '@/models/LogRecords';
import axios from 'axios';
import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useLogRecordStore = defineStore('log_records', () => {
    const defaultData: LogRecords = {
        levels: {choices: {}, selected: []},
        channels: {choices: {}, selected: []},
        logs: [],
        paginator: null
    };

    const loading = ref(false);
    const records = ref<LogRecords>(defaultData);

    async function fetch(file: string, levels: string[], channels: string[], direction: string, perPage: string, query: string, offset: number) {
        const params: { [key: string]: string } = {file, direction, per_page: perPage};

        if (query !== '') {
            params.query = query;
        }
        const levelChoices = Object.keys(records.value.levels.choices);
        if (levelChoices.length > 0 && levels.length !== levelChoices.length) {
            params.levels = levels.join(',');
        }
        const channelChoices = Object.keys(records.value.channels.choices);
        if (channelChoices.length > 0 && channels.length !== channelChoices.length) {
            params.channels = channels.join(',');
        }
        if (offset > 0) {
            params.offset = offset.toString();
        }
        loading.value = true;
        try {
            const response = await axios.get<LogRecords>('/api/logs', {params: params});
            records.value  = response.data;
        } catch (e) {
            console.error(e);
            records.value = defaultData;
        } finally {
            loading.value = false;
        }
    }

    return {loading, records, fetch}
});
