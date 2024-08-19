<script setup lang="ts">

const dateModel = defineModel<Date>();
defineProps<{ label: string }>();
const emit = defineEmits(['change']);

</script>

<template>
    <div class="input-group mt-3">
        <span class="input-group-text" id="absolute-date">{{ label }}</span>
        <input type="datetime-local" class="form-control" aria-describedby="absolute-date" value="2024-03-04">
    </div>
</template>
