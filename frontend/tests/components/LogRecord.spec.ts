import { mount } from '@vue/test-utils'
import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest'
import LogRecord from '../../src/components/LogRecord.vue'
import type LogRecordModel from '../../src/models/LogRecord'

const record: LogRecordModel = {
    datetime: '2026-08-09 14:30:00',
    level_name: 'Error',
    level_class: 'text-danger',
    channel: 'app',
    text: 'Request failed',
    context: { request_id: 'abc' },
    extra: { duration_ms: 120 }
}

const mountRecord = () =>
    mount(LogRecord, {
        props: { logRecord: record }
    })

describe('LogRecord', () => {
    const writeText = vi.fn()

    beforeEach(() => {
        vi.useFakeTimers()
        writeText.mockReset()
        writeText.mockResolvedValue(undefined)
        Object.defineProperty(navigator, 'clipboard', {
            configurable: true,
            value: { writeText }
        })
    })

    afterEach(() => {
        vi.useRealTimers()
    })

    test('copies through the button without toggling the row', async () => {
        const wrapper = mountRecord()
        const button = wrapper.get('button.slv-btn-copy')

        await button.trigger('click')

        expect(writeText).toHaveBeenCalledTimes(1)
        expect(wrapper.get('.slv-log-record').attributes('aria-expanded')).toBe('false')
    })
})
