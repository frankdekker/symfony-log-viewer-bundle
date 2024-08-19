<script setup lang="ts">
defineProps<{ label: string }>();
const emit = defineEmits(['change']);
</script>

<template>
    <div class="mt-3">
        <button class="btn btn-primary w-100" @click="emit('change', new Date())">
            Set {{ label.toLowerCase() }} to now
        </button>
    </div>
</template>
