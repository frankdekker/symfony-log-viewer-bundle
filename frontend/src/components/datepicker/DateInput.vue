<script setup lang="ts">
import {formatDateTime} from '@/services/Dates';

const date = defineModel<Date>({required: true});
defineProps<{ label: string }>();

const labelId = (Math.random() + 1).toString(36).substring(7);

function parseDate(evt: Event, update: boolean): void {
    const el         = <HTMLInputElement>evt.target;
    const dateString = el.value.trim();
    const timestamp  = Date.parse(dateString.replace(/\s*@\s*/, ' '));

    if (isNaN(timestamp)) {
        el.classList.toggle('is-invalid', true);
        el.setCustomValidity('Invalid date');
        el.reportValidity();
        return;
    }

    el.classList.toggle('is-invalid', false);
    el.setCustomValidity('');
    if (update) {
        date.value = new Date(timestamp);
    }
}
</script>

<template>
    <div class="input-group">
        <span class="input-group-text" :id="labelId">{{ label }}</span>
        <input type="text"
               class="form-control"
               :value="formatDateTime(date)"
               :aria-describedby="labelId"
               @change="evt => parseDate(evt, true)"
               @input="evt => parseDate(evt, false)">
    </div>
</template>
