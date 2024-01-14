import type LogFolder from '@/models/LogFolder'
import axios from 'axios'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useFolderStore = defineStore('folders', () => {
  const loading = ref(false)
  const direction = ref('desc')
  const folders = ref<LogFolder[]>(
    JSON.parse(document.head.querySelector<HTMLMetaElement>('[name=folders]')?.content ?? '[]')
  )

  async function update() {
    loading.value = true
    const response = await axios.get<LogFolder[]>('/api/folders', {
      params: { direction: direction.value }
    })
    folders.value = response.data
    loading.value = false
  }

  return { loading, direction, folders, update }
})
