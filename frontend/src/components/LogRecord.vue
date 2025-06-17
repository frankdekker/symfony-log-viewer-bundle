<script setup lang="ts">
import Json from '@/components/json/Json.vue';
import type LogRecord from '@/models/LogRecord';
import {isEmptyJson, prettyFormatJson} from '@/services/JsonFormatter';
import {ref} from 'vue';

const expanded = ref(false);
const styled = ref(true);
defineProps<{
    logRecord: LogRecord
}>()
</script>

<template>
    <div class="slv-list-group-item list-group-item list-group-item-action"
         :class="{'opacity-50': logRecord.context_line && !expanded}"
         :aria-expanded="expanded">
        <div class="slv-list-link" :class="{ 'text-nowrap': !expanded, 'overflow-hidden': !expanded }" @click="expanded = !expanded">
            <i class="slv-indicator bi bi-chevron-right me-1"></i>
            <span class="pe-2 text-secondary">{{ logRecord.datetime }}</span>
            <span class="text-primary pe-2" v-if="logRecord.channel.length > 0">{{ logRecord.channel }}</span>
            <span :class="['pe-2', logRecord.level_class ]">{{ logRecord.level_name }}</span>
            <span>{{ logRecord.text }}</span>
        </div>
        <div class="border-top pt-2 ps-4 mb-2" v-bind:class="{'d-block': expanded, 'd-none': !expanded}" v-if="expanded">
            <button class="btn btn-sm btn-outline-secondary ms-2" @click="styled = !styled">{{ styled ? 'raw' : 'styled' }}</button>
            <div v-if="!isEmptyJson(logRecord.context)">
                <div class="fw-bold">Context:</div>
                <json v-if="styled" :data=logRecord.context></json>
                <div v-else><pre class="ms-0"><code>{{ prettyFormatJson(logRecord.context )}}</code></pre></div>
            </div>
            <div v-if="!isEmptyJson(logRecord.extra)">
                <div class="fw-bold">Extra:</div>
                <json v-if="styled" :data=logRecord.extra></json>
                <div v-else><pre class="ms-0"><code>{{ prettyFormatJson(logRecord.extra )}}</code></pre></div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.slv-list-group-item {
    --bs-list-group-item-padding-x: 0px;
    --bs-list-group-item-padding-y: 0px;
}

.slv-list-link {
    cursor: pointer;
}
</style>
