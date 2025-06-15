<script setup lang="ts">
const query = defineModel();
const props = defineProps<{ invalid: boolean }>();
const emit  = defineEmits(['search']);
function clearSearch(): void {
    query.value = '';
    emit('search');
}
</script>

<template>
    <input type="text"
           class="form-control"
           :class="{'is-invalid': props.invalid}"
           ref="searchRef"
           placeholder="Search log entries."
           aria-label="Search log entries."
           aria-describedby="button-search"
           @keyup.enter="emit('search')"
           v-model="query">
    <div class="close-position" v-if="query !== ''">
        <button class="close btn btn-outline-secondary border-0" @click="clearSearch"><i class="bi bi-x"></i></button>
    </div>
</template>

<style scoped>
.close-position {
    position: relative;
    display: inline-block;
    width: 0;
}
.close {
    position: absolute;
    top: 2px;
    right: 1px;
}
</style>
