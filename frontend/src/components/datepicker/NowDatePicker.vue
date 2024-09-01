<script setup lang="ts">
import type DateSelection from '@/models/DateSelection';

const selected = defineModel<DateSelection>({required: true});
defineProps<{ label: string }>();

function update(): void {
    selected.value.date      = new Date();
    selected.value.mode      = 'now';
    selected.value.formatted = 'now';
}
</script>

<template>
    <div class="mt-5 mb-5">
        <button class="btn btn-primary w-100" @click="update()">
            Set {{ label.toLowerCase() }} to now
        </button>
    </div>
</template>
