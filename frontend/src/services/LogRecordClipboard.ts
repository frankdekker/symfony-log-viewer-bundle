import type LogRecord from '@/models/LogRecord'
import { prettyFormatJson } from '@/services/JsonFormatter'

function isEmpty(data: LogRecord['context']): boolean {
    if (typeof data === 'string') {
        const value = data.trim()

        return value === '' || value === '{}' || value === '[]'
    }

    return Object.keys(data).length === 0
}

export function formatLogRecordForClipboard(logRecord: LogRecord): string {
    const header = `[${logRecord.datetime}] ${logRecord.channel !== '' ? `${logRecord.channel}.` : ''}${logRecord.level_name}: ${logRecord.text}`
    const sections = [header]

    if (!isEmpty(logRecord.context)) {
        sections.push(`Context:\n${prettyFormatJson(logRecord.context)}`)
    }

    if (!isEmpty(logRecord.extra)) {
        sections.push(`Extra:\n${prettyFormatJson(logRecord.extra)}`)
    }

    return sections.join('\n\n')
}
