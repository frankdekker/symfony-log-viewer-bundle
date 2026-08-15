<script setup lang="ts">
import type LogRecord from '@/models/LogRecord'
import { formatLogRecordForClipboard } from '@/services/LogRecordClipboard'
import { onUnmounted, ref } from 'vue'

const props = defineProps<{ logRecord: LogRecord }>()
const copyState = ref<'idle' | 'copied' | 'error'>('idle')
const copyPending = ref(false)
let copyResetTimer: ReturnType<typeof setTimeout> | undefined

function clearCopyResetTimer(): void {
    if (copyResetTimer === undefined) {
        return
    }

    clearTimeout(copyResetTimer)
    copyResetTimer = undefined
}

function scheduleCopyReset(): void {
    clearCopyResetTimer()
    copyResetTimer = setTimeout(() => {
        copyState.value = 'idle'
        copyResetTimer = undefined
    }, 2000)
}

async function copyLogRecord(): Promise<void> {
    if (copyPending.value) {
        return
    }

    clearCopyResetTimer()
    copyPending.value = true

    try {
        if (typeof navigator === 'undefined' || navigator.clipboard === undefined) {
            throw new Error('Clipboard API unavailable')
        }

        await navigator.clipboard.writeText(formatLogRecordForClipboard(props.logRecord))
        copyState.value = 'copied'
    } catch(e) {
        console.error(e);
        copyState.value = 'error'
    } finally {
        copyPending.value = false
        scheduleCopyReset()
    }
}

function copyButtonLabel(): string {
    if (copyState.value === 'copied') {
        return 'Log entry copied'
    }

    if (copyState.value === 'error') {
        return 'Copy failed'
    }

    return 'Copy log entry'
}

onUnmounted(clearCopyResetTimer)
</script>

<template>
    <button
        class="btn btn-sm btn-outline-secondary border-0 flex-shrink-0 slv-btn-copy"
        type="button"
        :aria-label="copyButtonLabel()"
        :title="copyButtonLabel()"
        :disabled="copyPending"
        @click.stop="copyLogRecord"
    >
        <i
            class="bi"
            :class="{
                'bi-copy': copyState === 'idle',
                'bi-check-lg text-success': copyState === 'copied',
                'bi-x-lg text-danger': copyState === 'error'
            }"
            aria-hidden="true"
        ></i>
    </button>
</template>

<style scoped>
.slv-btn-copy {
    margin-left: auto;
    --bs-btn-padding-y: 0.125rem;
    --bs-btn-padding-x: 0.375rem;
}
</style>
