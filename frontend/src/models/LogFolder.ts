import type LogFile from '@/models/LogFile'

export default interface LogFolder {
  identifier: string
  path: string
  can_download: boolean
  can_delete: boolean
  files: LogFile[]
}
