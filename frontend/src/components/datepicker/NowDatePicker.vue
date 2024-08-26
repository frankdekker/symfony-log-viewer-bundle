<script setup lang="ts">
import type DateSelection from '@/models/DateSelection';

const selected = defineModel<DateSelection>({required: true});
defineProps<{ label: string }>();
</script>

<template>
    <div class="mt-5 mb-5">
        <button class="btn btn-primary w-100" @click="selected.date = new Date();selected.formatted = 'now'">
            Set {{ label.toLowerCase() }} to now
        </button>
    </div>
</template>
