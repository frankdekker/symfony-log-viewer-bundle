<script setup lang="ts">
import DropdownChecklist from '@/components/DropdownChecklist.vue';
import LogRecord from '@/components/LogRecord.vue';
import type Checklist from '@/models/Checklist';
import {filter} from '@/services/Objects';
import {nullify} from '@/services/Optional';
import {useLogRecordStore} from '@/stores/log_records';
import {onMounted, ref} from 'vue';
import {useRoute, useRouter} from 'vue-router';

const router         = useRouter();
const route          = useRoute();
const logRecordStore = useLogRecordStore();

const file     = ref('');
const query    = ref('');
const levels   = ref<Checklist>({choices: {}, selected: []});
const channels = ref<Checklist>({choices: {}, selected: []});
const perPage  = ref('50');
const sort     = ref('desc');
const offset   = ref(0);

const navigate = () => {
    const fileOffset = offset.value > 0 && logRecordStore.records.paginator?.direction !== sort.value ? 0 : offset.value;
    router.push({
        query: filter({
            file: file.value,
            query: nullify(query.value, ''),
            perPage: nullify(perPage.value, '50'),
            sort: nullify(sort.value, 'desc'),
            levels: nullify(levels.value.selected.join(','), Object.keys(logRecordStore.records.levels.choices).join(',')),
            channels: nullify(channels.value.selected.join(','), Object.keys(logRecordStore.records.channels.choices).join(',')),
            offset: nullify(fileOffset, 0)
        })
    });
}

const load = () => {
    logRecordStore
            .fetch(file.value, levels.value.selected, channels.value.selected, sort.value, perPage.value, query.value, offset.value)
            .then(() => {
                levels.value   = logRecordStore.records.levels;
                channels.value = logRecordStore.records.channels;
            });
}

onMounted(() => {
    file.value              = <string>route.query.file;
    query.value             = <string>(route.query.query ?? '');
    perPage.value           = <string>(route.query.perPage ?? '50');
    sort.value              = <string>(route.query.sort ?? 'desc');
    levels.value.selected   = (<string>(route.query.levels ?? '')).split(',');
    channels.value.selected = (<string>(route.query.channels ?? '')).split(',');
    offset.value            = parseInt(<string>(route.query.offset ?? '0'));
    load();
});
</script>

<template>
    <div class="lsv-content h-100 overflow-hidden lsv-loadable" v-bind:class="{ 'lsv-loading': logRecordStore.loading }">
        <div class="lsv-menu-grid pt-1">
            <DropdownChecklist label="Levels" :checklist="levels"></DropdownChecklist>
            <DropdownChecklist label="Channels" :checklist="channels"></DropdownChecklist>

            <div class="input-group">
                <input type="text"
                       class="form-control"
                       placeholder="Search log entries"
                       aria-label="Search log entries"
                       aria-describedby="button-search"
                       v-model="query">

                <select class="lsv-menu-sort-direction form-control" aria-label="Sort direction" title="Sort direction" v-model="sort">
                    <option value="desc">Newest First</option>
                    <option value="asc">Oldest First</option>
                </select>

                <select class="lsv-menu-page-size form-control" aria-label="Entries per page" title="Entries per page" v-model="perPage">
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="150">150</option>
                    <option value="200">200</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                </select>

                <button class="lsv-log-search-btn btn btn-outline-primary" type="button" id="button-search" @click="navigate">Search</button>
            </div>

            <button class="btn btn-dark ms-1 me-1" type="button" aria-label="Refresh" title="Refresh" @click="load">
                <i class="bi bi-arrow-clockwise"></i>
            </button>

            <div></div>
        </div>

        <main class="overflow-auto d-none d-md-block">
            <div class="lsv-entries list-group pt-1 pe-1 pb-3">
                <LogRecord :logRecord="record" v-for="record in logRecordStore.records.logs ?? []"></LogRecord>
            </div>
        </main>

        <footer class="pt-1 pb-1 d-flex" v-if="logRecordStore.records.paginator !== null">
            <button class="btn btn-sm btn-outline-secondary"
                    @click="offset = 0; navigate()"
                    v-bind:disabled="logRecordStore.records.paginator?.first === true">
                First
            </button>
            <button class="ms-2 btn btn-sm btn-outline-secondary"
                    @click="offset = logRecordStore.records.paginator?.offset ?? 0; navigate()"
                    v-bind:disabled="logRecordStore.records.paginator?.more === false">
                Next {{ perPage }}
            </button>

            <div class="lsv-spacer"></div>

            <div class="me-4 d-inline-block small" v-if="logRecordStore.records.performance !== undefined">
                <span class="small">Memory: {{ logRecordStore.records.performance.memoryUsage }}</span>
                &centerdot;
                <span class="small">Duration: {{ logRecordStore.records.performance.requestTime }}</span>
                &centerdot;
                <span class="small">Version: {{ logRecordStore.records.performance.version }}</span>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.lsv-spacer {
    flex-grow: 1;
}

.lsv-content {
    display: grid;
    grid-template-rows: auto 1fr auto;
}

.lsv-menu-grid {
    display: grid;
    grid-template-columns: auto auto 1fr auto auto;
}

.lsv-entries {
    --bs-list-group-border-radius: 0;
}

.lsv-menu-sort-direction, .lsv-menu-page-size, .lsv-log-search-btn {
    max-width: fit-content;
}

</style>
