<script setup lang="ts">
import SearchFilterService from '@/services/SearchFilterService';
import {onMounted, onUnmounted, ref} from 'vue';

const filterService = new SearchFilterService();
const expanded      = ref(false);
const emit          = defineEmits(['add']);

const addFilter = (event: UIEvent) => {
    const target = event.target as HTMLElement;
    const filter = target.closest('[data-role=filter]') as HTMLInputElement;
    const fields = Array.from(filter.querySelectorAll('input'));

    const [pattern, replaced] = filterService.createFilter(fields, filter.dataset.strip, String(filter.dataset.pattern));
    if (replaced) {
        emit('add', pattern);
    }
}

// on escape, close the dropdown if visible
const closeListener = function(event: KeyboardEvent) {
    if (event.key === 'Escape' && expanded.value === true) {
        event.preventDefault();
        expanded.value = !expanded.value;
    }
}
onMounted(() => document.addEventListener('keyup', closeListener));
onUnmounted(() => document.removeEventListener('keyup', closeListener));
</script>

<template>
    <button ref="filterButton"
            class="btn btn-outline-secondary dropdown-toggle"
            type="button"
            :aria-expanded="expanded"
            @click="expanded = !expanded">Filter
    </button>
    <div class="dropdown-menu slv-dropdown-menu" :class="{'d-block': expanded}">
        <div class="px-2">
            <div class="input-group mb-1" data-role="filter" data-pattern="before:{value}">
                <span class="slv-input-label input-group-text" id="filter-date-start">Before</span>
                <input name="value" type="datetime-local" class="form-control" aria-label="Before" aria-describedby="filter-date-start">
                <button class="btn btn-outline-primary" type="button" @click="addFilter">Add</button>
            </div>
            <div class="input-group mb-1" data-role="filter" data-pattern="after:{value}">
                <span class="slv-input-label input-group-text" id="filter-date-end">After</span>
                <input name="value" type="datetime-local" class="form-control" aria-label="After" aria-describedby="filter-date-end">
                <button class="btn btn-outline-primary" type="button" @click="addFilter">Add</button>
            </div>
            <div class="input-group mb-1" data-role="filter" data-pattern="severity:{value}" data-strip=" ">
                <span class="slv-input-label input-group-text" id="filter-severity">Severity</span>
                <input name="value"
                       type="text"
                       class="form-control"
                       placeholder="Separate multiple by pipe symbol"
                       @keyup.enter="addFilter"
                       aria-label="Severity"
                       aria-describedby="filter-severity">
                <button class="btn btn-outline-primary" type="button" @click="addFilter">Add</button>
            </div>
            <div class="input-group mb-1" data-role="filter" data-pattern="channel:{value}" data-strip=" ">
                <span class="slv-input-label input-group-text" id="filter-severity">Channels</span>
                <input name="value"
                       type="text"
                       class="form-control"
                       placeholder="Separate multiple by pipe symbol"
                       @keyup.enter="addFilter"
                       aria-label="Severity"
                       aria-describedby="filter-severity">
                <button class="btn btn-outline-primary" type="button" @click="addFilter">Add</button>
            </div>
            <div class="input-group mb-1" data-role="filter" data-pattern="exclude:{value}">
                <span class="slv-input-label input-group-text" id="filter-exclude">Exclude</span>
                <input name="value" type="text" class="form-control" aria-label="Exclude string" aria-describedby="filter-exclude">
                <button class="btn btn-outline-primary" type="button" @click="addFilter">Add</button>
            </div>
            <div class="input-group mb-1" data-role="filter" data-pattern="context:{key=}{value}">
                <span class="slv-input-label input-group-text" id="filter-context">Context</span>
                <input name="key"
                       type="text"
                       class="form-control"
                       placeholder="key (optional)"
                       aria-label="Context key (optional)"
                       aria-describedby="filter-context">
                <input name="value"
                       type="text"
                       class="form-control"
                       placeholder="search"
                       @keyup.enter="addFilter"
                       aria-label="Context"
                       aria-describedby="filter-context">
                <button class="btn btn-outline-primary" type="button" @click="addFilter">Add</button>
            </div>
            <div class="input-group mb-1" data-role="filter" data-pattern="extra:{key=}{value}">
                <span class="slv-input-label input-group-text" id="filter-extra">Extra</span>
                <input name="key"
                       type="text"
                       class="form-control"
                       placeholder="key (optional)"
                       aria-label="Extra key (optional)"
                       aria-describedby="filter-extra">
                <input name=value
                       type="text"
                       class="form-control"
                       placeholder="search"
                       @keyup.enter="addFilter"
                       aria-label="Extra"
                       aria-describedby="filter-extra">
                <button class="btn btn-outline-primary" type="button" @click="addFilter">Add</button>
            </div>
            <div>
                <button class="btn btn-sm btn-primary float-end" type="button" @click="expanded = !expanded">Close</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.slv-dropdown-menu {
    top: 37px;
}

.slv-input-label {
    width: 100px;
}
</style>
