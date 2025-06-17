<script setup lang="ts">
import Json from '@/components/json/Json.vue';
import type LogRecord from '@/models/LogRecord';
import {isEmptyJson, prettyFormatJson} from '@/services/JsonFormatter';
import {ref} from 'vue';

const expanded = ref(false);
const styled   = ref(true);
defineProps<{
    logRecord: LogRecord
}>()

function click(value: unknown): void {
    console.log('record', value);
}

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
        <div class="border-top pt-2 ps-2 mb-2 position-relative" v-bind:class="{'d-block': expanded, 'd-none': !expanded}" v-if="expanded">
            <button class="btn btn-outline-secondary slv-btn-raw" @click="styled = !styled">{{ styled ? 'raw' : 'styled' }}</button>
            <div v-if="!isEmptyJson(logRecord.context)">
                <div class="fw-bold">Context:</div>
                <json v-if="styled" path="context:" :data=logRecord.context @click="click"></json>
                <div v-else>
                    <pre class="ms-0"><code>{{ prettyFormatJson(logRecord.context) }}</code></pre>
                </div>
            </div>
            <div v-if="!isEmptyJson(logRecord.extra)">
                <div class="fw-bold">Extra:</div>
                <json v-if="styled" path="extra:" :data=logRecord.extra @click="click"></json>
                <div v-else>
                    <pre class="ms-0"><code>{{ prettyFormatJson(logRecord.extra) }}</code></pre>
                </div>
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

.slv-btn-raw {
    position: absolute;
    top: 5px;
    right: 5px;
    --bs-btn-padding-y: .25rem;
    --bs-btn-padding-x: .5rem;
    --bs-btn-font-size: .75rem;
}
</style>
