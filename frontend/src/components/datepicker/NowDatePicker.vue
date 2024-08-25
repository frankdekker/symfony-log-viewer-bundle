<script setup lang="ts">
const selectedDate = defineModel<Date>({required: true});
defineProps<{ label: string }>();
</script>

<template>
    <div class="mt-5 mb-5">
        <button class="btn btn-primary w-100" @click="selectedDate = new Date()">
            Set {{ label.toLowerCase() }} to now
        </button>
    </div>
</template>
