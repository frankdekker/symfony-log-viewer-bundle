<script setup lang="ts">
import {ref} from 'vue';

const expanded = ref(false);
const emit     = defineEmits(['add']);

const addFilter = (event: MouseEvent) => {
    const target = <HTMLElement>event.target;
    const filter = <HTMLInputElement>target.closest('[data-role=filter]');
    const fields = Array.from(filter.querySelectorAll('input'));
    let pattern  = String(filter.dataset.pattern);
    const strip  = filter.dataset.strip;
    let replaced = false;

    for (const input of fields) {
        const key = input.name;
        let val   = input.value.trim();
        if (strip !== undefined) {
            val = val.replace(strip, '');
        }

        const escapeVal = (val.indexOf(' ') === -1 ? val : '"' + val + '"');
        const matches   = pattern.match('\\{' + key + '(=)?\\}');
        if (matches !== null) {
            pattern = pattern.replace(matches[0], val === '' ? '' : escapeVal + (matches[1] ?? ''));
        }
        input.value = '';
        replaced    = replaced || val !== '';
    }

    if (replaced) {
        emit('add', pattern);
    }
}

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
                <input name="value" type="text" class="form-control" placeholder="search" aria-label="Context" aria-describedby="filter-context">
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
                <input name=value type="text" class="form-control" placeholder="search" aria-label="Extra" aria-describedby="filter-extra">
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
