<script setup lang="ts">
import FileTree from '@/components/FileTree.vue'
import { useRoute } from 'vue-router'

const route = useRoute()
</script>

<template>
  <div class="slv-sidebar h-100 overflow-hidden">
    <header class="slv-header-height bg-body">
      <h3 class="d-block text-center slv-app-title">
        <i class="bi bi-substack slv-icon-color"></i>
        Log viewer
      </h3>
    </header>

    <FileTree />
  </div>
  <RouterView :key="route.fullPath"></RouterView>
</template>

<style scoped>
.slv-sidebar {
  display: grid;
  grid-template-rows: auto 1fr;
}

.slv-icon-color {
  color: #fff;
}
</style>
