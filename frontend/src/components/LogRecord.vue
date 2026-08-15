<script setup lang="ts">
import ClipboardButton from '@/components/ClipboardButton.vue'
import JsonData from '@/components/json/JsonData.vue'
import type LogRecord from '@/models/LogRecord'
import { nl2br, escapeHtml } from '@/services/Strings.ts'
import { isEmptyJson, prettyFormatJson } from '@/services/JsonFormatter'
import { ref } from 'vue'

const expanded = ref(false)
const styled = ref(true)
const props = defineProps<{ logRecord: LogRecord }>()
const emit = defineEmits(['search'])

function click(value: string) {
    emit('search', value)
}
</script>

<template>
    <div
        class="slv-log-record"
        :class="{ 'opacity-50': props.logRecord.context_line && !expanded }"
        :aria-expanded="expanded"
    >
        <div
            class="slv-list-link list-group-item list-group-item-action border-0 p-0"
            :class="{ 'text-nowrap': !expanded, 'overflow-hidden': !expanded }"
            @click="expanded = !expanded"
        >
            <i class="slv-indicator bi bi-chevron-right me-1"></i>
            <span class="pe-2 text-secondary">{{ props.logRecord.datetime }}</span>
            <span class="text-primary pe-2" v-if="props.logRecord.channel.length > 0">{{
                props.logRecord.channel
            }}</span>
            <span :class="['pe-2', props.logRecord.level_class]">{{
                props.logRecord.level_name
            }}</span>

            <!-- log message -->
            <span
                class="slv-log-message"
                v-if="!expanded"
                v-text="props.logRecord.text.substring(0, 500)"
            ></span>
            <span
                class="slv-log-message"
                v-if="expanded"
                v-html="nl2br(escapeHtml(props.logRecord.text))"
            ></span>
            <clipboard-button :log-record="props.logRecord" />
        </div>
        <div
            class="border-top pt-2 ps-2 mb-2 position-relative"
            v-bind:class="{ 'd-block': expanded, 'd-none': !expanded }"
            v-if="expanded"
        >
            <button class="btn btn-outline-secondary slv-btn-raw" @click="styled = !styled">
                {{ styled ? 'raw' : 'styled' }}
            </button>
            <div v-if="!isEmptyJson(props.logRecord.context)">
                <div class="fw-bold">Context:</div>
                <json-data
                    v-if="styled"
                    path="context:"
                    :data="props.logRecord.context"
                    @click="click"
                ></json-data>
                <div v-else>
                    <pre
                        class="ms-0"
                    ><code>{{ prettyFormatJson(props.logRecord.context) }}</code></pre>
                </div>
            </div>
            <div v-if="!isEmptyJson(props.logRecord.extra)">
                <div class="fw-bold">Extra:</div>
                <json-data
                    v-if="styled"
                    path="extra:"
                    :data="props.logRecord.extra"
                    @click="click"
                ></json-data>
                <div v-else>
                    <pre
                        class="ms-0"
                    ><code>{{ prettyFormatJson(props.logRecord.extra) }}</code></pre>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.slv-log-record {
    border: 1px solid var(--bs-border-color);
    border-bottom-width: 0;
}

.slv-log-record:last-child {
    border-bottom-width: 1px;
}

.slv-list-link {
    align-items: flex-start;
    cursor: pointer;
    display: flex;
}

.slv-log-message {
    flex: 1 1 auto;
    min-width: 0;
    overflow: hidden;
}

.slv-btn-raw {
    position: absolute;
    top: 5px;
    right: 5px;
    --bs-btn-padding-y: 0.25rem;
    --bs-btn-padding-x: 0.5rem;
    --bs-btn-font-size: 0.75rem;
}
</style>
