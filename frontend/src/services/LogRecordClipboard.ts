import type LogRecord from '@/models/LogRecord'
import {isEmptyJson, prettyFormatJson} from '@/services/JsonFormatter'

export function formatLogRecordForClipboard(logRecord: LogRecord): string {
    const header = `[${logRecord.datetime}] ${logRecord.channel !== '' ? `${logRecord.channel}.` : ''}${logRecord.level_name}: ${logRecord.text}`
    const sections = [header]

    if (!isEmptyJson(logRecord.context)) {
        sections.push(`Context:\n${prettyFormatJson(logRecord.context)}`)
    }

    if (!isEmptyJson(logRecord.extra)) {
        sections.push(`Extra:\n${prettyFormatJson(logRecord.extra)}`)
    }

    return sections.join('\n\n')
}
