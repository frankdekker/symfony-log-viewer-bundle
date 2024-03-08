<script setup lang="ts">
import {ref} from 'vue';

const expanded = ref(false);

</script>

<template>
    <button ref="filterButton"
            class="btn btn-outline-secondary dropdown-toggle"
            type="button"
            :aria-expanded="expanded"
            @click="expanded = !expanded">Filter
    </button>
    <div class="dropdown-menu" :class="{'d-block': expanded}">
        <div class="px-2">
            <div class="input-group mb-1">
                <span class="slv-input-label input-group-text" id="filter-date-start">Date start</span>
                <input type="datetime-local" class="form-control" aria-label="Date start" aria-describedby="filter-date-start">
                <button class="btn btn-outline-primary" type="button">Add</button>
            </div>
            <div class="input-group mb-1">
                <span class="slv-input-label input-group-text" id="filter-date-end">Date end</span>
                <input type="datetime-local" class="form-control" aria-label="Date end" aria-describedby="filter-date-end">
                <button class="btn btn-outline-primary" type="button">Add</button>
            </div>
            <div class="input-group mb-1">
                <span class="slv-input-label input-group-text" id="filter-severity">Severity</span>
                <input type="text"
                       class="form-control"
                       placeholder="Separate multiple by pipe symbol"
                       aria-label="Severity"
                       aria-describedby="filter-severity">
                <button class="btn btn-outline-primary" type="button">Add</button>
            </div>
            <div class="input-group mb-1">
                <span class="slv-input-label input-group-text" id="filter-severity">Channels</span>
                <input type="text"
                       class="form-control"
                       placeholder="Separate multiple by pipe symbol"
                       aria-label="Severity"
                       aria-describedby="filter-severity">
                <button class="btn btn-outline-primary" type="button">Add</button>
            </div>
            <div class="input-group mb-1">
                <span class="slv-input-label input-group-text" id="filter-exclude">Exclude</span>
                <input type="text" class="form-control" aria-label="Exclude string" aria-describedby="filter-exclude">
                <button class="btn btn-outline-primary" type="button">Add</button>
            </div>
            <div class="input-group mb-1">
                <span class="slv-input-label input-group-text" id="filter-context">Context</span>
                <input type="text" class="form-control" aria-label="Context key (optional)" aria-describedby="filter-context">
                <input type="text" class="form-control" aria-label="Context" aria-describedby="filter-context">
                <button class="btn btn-outline-primary" type="button">Add</button>
            </div>
            <div class="input-group mb-1">
                <span class="slv-input-label input-group-text" id="filter-extra">Extra</span>
                <input type="text" class="form-control" aria-label="Extra key (optional)" aria-describedby="filter-extra">
                <input type="text" class="form-control" aria-label="Extra" aria-describedby="filter-extra">
                <button class="btn btn-outline-primary" type="button">Add</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.slv-input-label {
    width: 100px;
}
</style>
