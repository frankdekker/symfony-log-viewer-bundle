<script setup lang="ts">
import Foldout from '@/services/Foldout';
import {ref} from 'vue';

const filterButton   = ref<HTMLElement>();
const filterDropdown = ref<HTMLElement>();
const searchRef      = ref<HTMLInputElement>();
const query          = defineModel<string>('query');
const sort           = defineModel<string>('sort');
const perPage        = defineModel<string>('perPage');

const focus = (): void => {
    searchRef.value?.focus();
}

defineProps<{
    badRequest: boolean,
}>();
defineExpose({focus});

new Foldout(filterButton, filterDropdown).bind();

</script>

<template>
    <div class="input-group">
        <button ref="filterButton" class="btn btn-outline-secondary dropdown-toggle" type="button" aria-expanded="false">Filter</button>
        <ul class="dropdown-menu" ref="filterDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">Separated link</a></li>
        </ul>

        <input type="text"
               class="form-control"
               :class="{'is-invalid': badRequest}"
               ref="searchRef"
               placeholder="Search log entries, Use severity:, channel:, before:, after:, or exclude: to fine-tune the search."
               aria-label="Search log entries, Use severity:, channel:, before:, after:, or exclude: to fine-tune the search."
               aria-describedby="button-search"
               @change="$emit('navigate')"
               v-model="query">

        <select class="slv-menu-sort-direction form-control"
                aria-label="Sort direction"
                title="Sort direction"
                v-model="sort"
                @change="$emit('navigate')">
            <option value="desc">Newest First</option>
            <option value="asc">Oldest First</option>
        </select>

        <select class="slv-menu-page-size form-control"
                aria-label="Entries per page"
                title="Entries per page"
                v-model="perPage"
                @change="$emit('navigate')">
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="150">150</option>
            <option value="200">200</option>
            <option value="250">250</option>
            <option value="300">300</option>
        </select>

        <button class="slv-log-search-btn btn btn-outline-primary" type="button" id="button-search" @click="$emit('navigate')">Search</button>
    </div>
</template>

<style scoped>
.slv-menu-sort-direction, .slv-menu-page-size, .slv-log-search-btn {
    max-width: fit-content;
}
</style>
