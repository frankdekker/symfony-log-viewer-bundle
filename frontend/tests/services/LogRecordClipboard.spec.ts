import { describe, expect, test } from 'vitest'
import { formatLogRecordForClipboard } from '../../src/services/LogRecordClipboard'
import type LogRecord from '../../src/models/LogRecord'

const record = (overrides: Partial<LogRecord> = {}): LogRecord => ({
    datetime: '2026-08-09 14:30:00',
    level_name: 'Error',
    level_class: 'text-danger',
    channel: 'app',
    text: 'Request failed',
    context: {},
    extra: {},
    ...overrides
})

describe('LogRecordClipboard', () => {
    test('formats a complete record with readable context and extra sections', () => {
        expect(
            formatLogRecordForClipboard(
                record({
                    context: { request_id: 'abc' },
                    extra: { duration_ms: 120 }
                })
            )
        ).toBe(`[2026-08-09 14:30:00] app.Error: Request failed

Context:
{
  "request_id": "abc"
}

Extra:
{
  "duration_ms": 120
}`)
    })

    test('omits empty and whitespace-only context and extra', () => {
        expect(formatLogRecordForClipboard(record({ context: ' \n ', extra: ' {} ' }))).toBe(
            '[2026-08-09 14:30:00] app.Error: Request failed'
        )
    })

    test('uses the compact header when the channel is empty', () => {
        expect(
            formatLogRecordForClipboard(
                record({ channel: '', level_name: '500', text: 'GET /health' })
            )
        ).toBe('[2026-08-09 14:30:00] 500: GET /health')
    })

    test('preserves message whitespace and unparseable section values', () => {
        expect(
            formatLogRecordForClipboard(
                record({
                    text: '  first line\nsecond line  ',
                    extra: '{"foo"}'
                })
            )
        ).toBe(`[2026-08-09 14:30:00] app.Error:   first line
second line  

Extra:
{"foo"}`)
    })
})
