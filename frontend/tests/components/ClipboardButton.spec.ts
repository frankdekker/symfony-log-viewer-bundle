import { mount } from '@vue/test-utils'
import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest'
import ClipboardButton from '../../src/components/ClipboardButton.vue'
import type LogRecord from '../../src/models/LogRecord'
import { formatLogRecordForClipboard } from '../../src/services/LogRecordClipboard'

const record: LogRecord = {
    datetime: '2026-08-09 14:30:00',
    level_name: 'Error',
    level_class: 'text-danger',
    channel: 'app',
    text: 'Request failed',
    context: { request_id: 'abc' },
    extra: { duration_ms: 120 }
}

const mountButton = () =>
    mount(ClipboardButton, {
        props: { logRecord: record }
    })

describe('ClipboardButton', () => {
    const writeText = vi.fn()

    beforeEach(() => {
        vi.useFakeTimers()
        writeText.mockReset()
        Object.defineProperty(navigator, 'clipboard', {
            configurable: true,
            value: { writeText }
        })
    })

    afterEach(() => {
        vi.useRealTimers()
    })

    test('copies the formatted log record and shows success feedback', async () => {
        writeText.mockResolvedValue(undefined)
        const wrapper = mountButton()

        await wrapper.get('button').trigger('click')

        expect(writeText).toHaveBeenCalledWith(formatLogRecordForClipboard(record))
        expect(wrapper.get('button').attributes('aria-label')).toBe('Log entry copied')
        expect(wrapper.get('i').classes()).toContain('bi-check-lg')
        expect(wrapper.get('i').classes()).toContain('text-success')
    })

    test('shows an error state when the clipboard write fails', async () => {
        writeText.mockRejectedValue(new Error('denied'))
        const wrapper = mountButton()

        await wrapper.get('button').trigger('click')

        expect(wrapper.get('button').attributes('aria-label')).toBe('Copy failed')
        expect(wrapper.get('i').classes()).toContain('bi-x-lg')
        expect(wrapper.get('i').classes()).toContain('text-danger')
    })

    test('ignores another click while a copy is pending', async () => {
        let resolveWriteText: () => void = () => undefined
        writeText.mockImplementation(
            () =>
                new Promise<void>((resolve) => {
                    resolveWriteText = resolve
                })
        )
        const wrapper = mountButton()
        const button = wrapper.get('button')

        await button.trigger('click')
        await button.trigger('click')

        expect(writeText).toHaveBeenCalledTimes(1)
        resolveWriteText()
        await vi.waitFor(() => expect(button.attributes('aria-label')).toBe('Log entry copied'))
    })

    test('resets feedback after two seconds and allows an immediate retry', async () => {
        writeText.mockResolvedValue(undefined)
        const wrapper = mountButton()
        const button = wrapper.get('button')

        await button.trigger('click')
        expect(button.attributes('aria-label')).toBe('Log entry copied')

        await vi.advanceTimersByTimeAsync(1000)
        await button.trigger('click')
        expect(writeText).toHaveBeenCalledTimes(2)
        expect(button.attributes('aria-label')).toBe('Log entry copied')

        await vi.advanceTimersByTimeAsync(1999)
        expect(button.attributes('aria-label')).toBe('Log entry copied')
        await vi.advanceTimersByTimeAsync(1)
        expect(button.attributes('aria-label')).toBe('Copy log entry')
    })
})
