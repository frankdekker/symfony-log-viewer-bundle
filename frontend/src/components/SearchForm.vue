<script setup lang="ts">
import DatePicker from '@/components/datepicker/DatePicker.vue';
import SearchFilter from '@/components/SearchFilter.vue';
import SearchInput from '@/components/SearchInput.vue';
import {ref} from 'vue';

const searchRef = ref<HTMLInputElement>();
const query     = defineModel<string>('query');
const between   = defineModel<string>('between', {default: ''});
const sort      = defineModel<string>('sort');
const perPage   = defineModel<string>('perPage');
const emit      = defineEmits(['navigate']);
defineProps<{ badRequest: boolean }>();
const focus = (): void => searchRef.value?.focus()
defineExpose({focus});
</script>

<template>
    <div class="input-group">
        <search-filter @add="(value) => { query = (query === '' ? value : query + ' ' + value); emit('navigate'); }"/>
        <search-input :invalid=badRequest @search="emit('navigate')" v-model="query" />
        <date-picker class="slv-date-picker" v-model="between" @change="emit('navigate')"/>

        <select class="slv-menu-sort-direction form-control"
                aria-label="Sort direction"
                title="Sort direction"
                v-model="sort"
                @change="emit('navigate')">
            <option value="desc">Newest First</option>
            <option value="asc">Oldest First</option>
        </select>

        <select class="slv-menu-page-size form-control"
                aria-label="Entries per page"
                title="Entries per page"
                v-model="perPage"
                @change="emit('navigate')">
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="150">150</option>
            <option value="200">200</option>
            <option value="250">250</option>
            <option value="300">300</option>
        </select>

        <button class="slv-log-search-btn btn btn-outline-primary" type="button" id="button-search" @click="emit('navigate')">Search</button>
    </div>
</template>

<style scoped>
.slv-menu-sort-direction, .slv-menu-page-size, .slv-log-search-btn, .slv-date-picker {
    max-width: fit-content;
}
</style>
