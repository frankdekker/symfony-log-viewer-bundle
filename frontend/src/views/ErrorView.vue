<template>
    <div class="failure">
        <div class="alert alert-danger label">
            An error occurred while reading the log file.
        </div>
    </div>
</template>

<style scoped>
.failure {
    position: relative;

    .label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
}
</style>
<script setup lang="ts"></script>
