<script setup lang="ts">
import {getRelativeDate} from '@/services/Dates';
import Numbers from '@/services/Numbers';
import {ref} from 'vue';

const dateModel = defineModel<Date>();
const valueRef  = ref<HTMLInputElement>();
const unitRef   = ref<HTMLSelectElement>();

function validate(payload: Event): void {
    const el = <HTMLInputElement>payload.target;
    el.classList.toggle('is-invalid', el.checkValidity() === false);
}

function onValueChanged(): void {
    const valueInput = <HTMLInputElement>valueRef.value;
    const unitInput  = <HTMLSelectElement>unitRef.value;
    if (valueInput.checkValidity() === false) {
        return;
    }

    dateModel.value = getRelativeDate(Numbers.parseInt(valueInput.value), unitInput.value, true);
}

</script>

<template>
    <div class="d-flex flex-row mt-5 mb-5" @input="onValueChanged" @change="onValueChanged">
        <input type="number"
               ref="valueRef"
               class="flex-fill form-control me-1 value-input"
               value="15"
               min="0"
               pattern="^[1-9][0-9]*$"
               @input="validate"
               required
               aria-required="true">
        <select class="flex-fill form-control" ref="unitRef">
            <option value="s">Seconds ago</option>
            <option value="i" selected>Minutes ago</option>
            <option value="h">Hours ago</option>
            <option value="d">Days ago</option>
            <option value="w">Weeks ago</option>
            <option value="m">Months ago</option>
            <option value="y">Years ago</option>
        </select>
    </div>
</template>

<style scoped>
.value-input {
    width: 100px;
}
</style>
