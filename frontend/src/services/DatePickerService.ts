import type DateSelection from '@/models/DateSelection';
import {format, formatDateTime, formatRelativeDate, getRelativeDate} from '@/services/Dates';
import Numbers from '@/services/Numbers';

export function parseSelection(value: string, startDate: DateSelection, endDate: DateSelection): boolean {
    const match = (value ?? '').trim().match(/^(.*)~(.*)$/);
    if (match === null || match.length !== 3) {
        return false;
    }
    return parseDate(match[1], startDate) && parseDate(match[2], endDate);
}

export function formatSelection(startDate: DateSelection, endDate: DateSelection): string {
    return `${formatDate(startDate)}~${formatDate(endDate)}`;
}

function parseDate(value: string, date: DateSelection): boolean {
    if (value === 'now') {
        date.date      = new Date();
        date.mode      = 'now';
        date.value     = null;
        date.formatted = 'now';
        return true;
    }
    if (value.match(/^\d{4}-\d{2}-\d{2} \d{1,2}:\d{2}:\d{2}$/)) {
        date.date      = new Date(value);
        date.mode      = 'absolute';
        date.value     = null;
        date.formatted = formatDateTime(date.date);
        return true;
    }
    if (value.match(/^\d+[sihdwmy]$/)) {
        const unit     = value[value.length - 1];
        const val      = Numbers.parseInt(value.substring(0, value.length - 1));
        date.date      = getRelativeDate(val, unit, false);
        date.mode      = 'relative';
        date.value     = value;
        date.formatted = formatRelativeDate(val, unit);
        return true;
    }

    return false;
}

function formatDate(date: DateSelection): string {
    switch (date.mode) {
        case 'now':
            return 'now';
        case 'relative':
            return date.value ?? '';
        case 'absolute':
            return format('Y-m-d H:i:s', date.date);
        default:
            throw new Error('Invalid date selection mode: ' + date.mode);
    }
}
