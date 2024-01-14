import type { Emitter } from 'mitt'
import mitt from 'mitt'

const emitter: Emitter<Record<string, unknown>> = mitt()

export default emitter
