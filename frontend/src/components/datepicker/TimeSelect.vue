<script setup lang="ts">
import {getHours} from '@/services/Dates';

const selectedDate = defineModel<Date>({required: true});

function onUpdate(date: Date): void {
    selectedDate.value.setHours(date.getHours());
    selectedDate.value.setMinutes(date.getMinutes());
    selectedDate.value.setSeconds(0);
    selectedDate.value.setMilliseconds(0);
    selectedDate.value = new Date(selectedDate.value);
}
</script>

<template>
    <div class="overflow-auto">
        <button class="btn btn-outline-primary btn-sm border-0 d-block"
                :class="{'btn-outline-primary-active': selectedDate.getHours() === hour.getHours() && selectedDate.getMinutes() === hour.getMinutes()}"
                v-bind:key="hour.getHours() + ':' + hour.getMinutes()"
                v-for="hour in getHours()"
                :data-hour="hour.getHours()"
                :data-minute="hour.getMinutes()"
                @click="onUpdate(hour)">
            {{ hour.getHours().toString().padStart(2, '0') }}:{{ hour.getMinutes().toString().padStart(2, '0') }}
        </button>
    </div>
</template>
