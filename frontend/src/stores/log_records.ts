import type LogRecords from '@/models/LogRecords';
import type ParameterBag from '@/models/ParameterBag';
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

    async function fetch(params: ParameterBag, loadingIndicator: boolean) {
        loading.value = loadingIndicator && true;
        try {
            const response = await axios.get<LogRecords>('/api/logs', {params: params.all()});
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
