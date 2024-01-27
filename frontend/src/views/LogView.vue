<script setup lang="ts">
import LogRecord from '@/components/LogRecord.vue';
import PerformanceDetails from '@/components/PerformanceDetails.vue';
import {filter} from '@/services/Objects';
import {nullify} from '@/services/Optional';
import {useLogRecordStore} from '@/stores/log_records';
import {useSearchStore} from '@/stores/search';
import {onMounted, ref} from 'vue';
import {useRoute, useRouter} from 'vue-router';

const router         = useRouter();
const route          = useRoute();
const logRecordStore = useLogRecordStore();
const searchStore    = useSearchStore();

const searchRef  = ref<HTMLInputElement>();
const file       = ref('');
const offset     = ref(0);
const badRequest = ref(false);

const navigate = () => {
    const fileOffset = offset.value > 0 && logRecordStore.records.paginator?.direction !== searchStore.sort ? 0 : offset.value;
    router.push({
        query: filter({
            file: file.value,
            query: nullify(searchStore.query, ''),
            perPage: nullify(searchStore.perPage, '50'),
            sort: nullify(searchStore.sort, 'desc'),
            offset: nullify(fileOffset, 0)
        })
    });
}

const load = () => {
    badRequest.value = false;
    logRecordStore
        .fetch(file.value, searchStore.sort, searchStore.perPage, searchStore.query, offset.value)
        .catch((error: Error) => {
            if (error.message === 'bad-request') {
                badRequest.value = true;
                return;
            }
            router.push({name: error.message});
        })
        .finally(() => {
            searchRef.value?.focus();
        });
}

onMounted(() => {
    file.value          = String(route.query.file);
    searchStore.query   = String((route.query.query ?? ''));
    searchStore.perPage = String((route.query.perPage ?? '50'));
    searchStore.sort    = String((route.query.sort ?? 'desc'));
    offset.value        = parseInt(String(route.query.offset ?? '0'));
    load();
});
</script>

<template>
    <div class="slv-content h-100 overflow-hidden">
        <div class="d-flex align-items-stretch pt-1">
            <div class="flex-grow-1 input-group">
                <input type="text"
                       class="form-control"
                       :class="{'is-invalid': badRequest}"
                       ref="searchRef"
                       placeholder="Search log entries, Use severity:, channel:, before:, after:, or exclude: to fine-tune the search."
                       aria-label="Search log entries, Use severity:, channel:, before:, after:, or exclude: to fine-tune the search."
                       aria-describedby="button-search"
                       @change="navigate"
                       v-model="searchStore.query">

                <select class="slv-menu-sort-direction form-control"
                        aria-label="Sort direction"
                        title="Sort direction"
                        v-model="searchStore.sort"
                        @change="navigate">
                    <option value="desc">Newest First</option>
                    <option value="asc">Oldest First</option>
                </select>

                <select class="slv-menu-page-size form-control"
                        aria-label="Entries per page"
                        title="Entries per page"
                        v-model="searchStore.perPage"
                        @change="navigate">
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="150">150</option>
                    <option value="200">200</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                </select>

                <button class="slv-log-search-btn btn btn-outline-primary" type="button" id="button-search" @click="navigate">Search</button>
            </div>

            <button class="btn btn-dark ms-1 me-1" type="button" aria-label="Refresh" title="Refresh" @click="load">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>

        <main class="overflow-auto d-none d-md-block slv-loadable" v-bind:class="{ 'slv-loading': logRecordStore.loading }">
            <div class="slv-entries list-group pt-1 pe-1 pb-3">
                <log-record :logRecord="record" v-for="(record, index) in logRecordStore.records.logs ?? []" v-bind:key="index"></log-record>
            </div>
        </main>

        <footer class="pt-1 pb-1 d-flex" v-show="!logRecordStore.loading">
            <button class="btn btn-sm btn-outline-secondary"
                    @click="offset = 0; navigate()"
                    v-bind:disabled="logRecordStore.records.paginator?.first !== false">
                First
            </button>
            <button class="ms-2 btn btn-sm btn-outline-secondary"
                    @click="offset = logRecordStore.records.paginator?.offset ?? 0; navigate()"
                    v-bind:disabled="logRecordStore.records.paginator?.more !== true">
                Next {{ searchStore.perPage }}
            </button>

            <div class="flex-grow-1"></div>

            <performance-details :performance="logRecordStore.records.performance"></performance-details>
        </footer>
    </div>
</template>

<style scoped>
.slv-content {
    display: grid;
    grid-template-rows: auto 1fr auto;
}

.slv-entries {
    --bs-list-group-border-radius: 0;
}

.slv-menu-sort-direction, .slv-menu-page-size, .slv-log-search-btn {
    max-width: fit-content;
}
</style>
