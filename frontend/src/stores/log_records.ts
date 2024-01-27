import type LogRecords from '@/models/LogRecords';
import axios, {AxiosError} from 'axios';
import {defineStore} from 'pinia'
import {ref} from 'vue'

export const useLogRecordStore = defineStore('log_records', () => {
    const defaultData: LogRecords = {
        logs: [],
        paginator: null
    };

    const loading = ref(false);
    const records = ref<LogRecords>(defaultData);

    async function fetch(file: string, direction: string, perPage: string, query: string, offset: number) {
        const params: { [key: string]: string } = {file, direction, per_page: perPage};

        if (query !== '') {
            params.query = query;
        }

        if (offset > 0) {
            params.offset = offset.toString();
        }

        loading.value = true;
        try {
            const response = await axios.get<LogRecords>('/api/logs', {params: params});
            records.value  = response.data;
        } catch (e) {
            if (e instanceof AxiosError && e.response?.status === 400) {
                throw new Error('bad-request');
            }
            if (e instanceof AxiosError && e.response?.status === 404) {
                throw new Error('file-not-found');
            }
            if (e instanceof AxiosError && [500, 501, 502, 503, 504].includes(Number(e.response?.status))) {
                throw new Error('error');
            }
            console.error(e);
            records.value = defaultData;
        } finally {
            loading.value = false;
        }
    }

    return {loading, records, fetch}
});
