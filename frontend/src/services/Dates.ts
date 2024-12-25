export function getRelativeDate(value: number, unit: string, floor: boolean): Date {
    // calculate start date
    const date = new Date();
    switch (unit) {
        case 's':
            date.setSeconds(date.getSeconds() - value);
            if (floor) {
                date.setMilliseconds(0);
            }
            break;
        case 'i':
            date.setMinutes(date.getMinutes() - value);
            if (floor) {
                date.setSeconds(0);
                date.setMilliseconds(0);
            }
            break;
        case 'h':
            date.setHours(date.getHours() - value);
            if (floor) {
                date.setMinutes(0);
                date.setSeconds(0);
                date.setMilliseconds(0);
            }
            break;
        case 'd':
            date.setDate(date.getDate() - value);
            if (floor) {
                date.setHours(0);
                date.setMinutes(0);
                date.setSeconds(0);
                date.setMilliseconds(0);
            }
            break;
        case 'w':
            date.setDate(date.getDate() - (value * 7));
            if (floor) {
                date.setHours(0);
                date.setMinutes(0);
                date.setSeconds(0);
                date.setMilliseconds(0);
            }
            break;
        case 'm':
            date.setMonth(date.getMonth() - value);
            if (floor) {
                date.setDate(1);
                date.setHours(0);
                date.setMinutes(0);
                date.setSeconds(0);
                date.setMilliseconds(0);
            }
            break;
        case 'y':
            date.setFullYear(date.getFullYear() - value);
            if (floor) {
                date.setMonth(0);
                date.setDate(1);
                date.setHours(0);
                date.setMinutes(0);
                date.setSeconds(0);
                date.setMilliseconds(0);
            }
            break;
    }
    return date;
}

export function formatDateTime(date: Date | undefined): string {
    if (date === undefined) {
        return '';
    }

    const formattedDate = date.toLocaleDateString(undefined, {year: 'numeric', month: 'short', day: 'numeric'});
    const formattedTime = format('H:i:s.u', date);

    return `${formattedDate} @ ${formattedTime}`;
}

export function formatRelativeDate(value: number, unit: string): string {
    switch (unit) {
        case 's':
            return `~${value} seconds ago`;
        case 'i':
            return `~${value} minutes ago`;
        case 'h':
            return `~${value} hours ago`;
        case 'd':
            return `~${value} days ago`;
        case 'w':
            return `~${value} weeks ago`;
        case 'm':
            return `~${value} months ago`;
        case 'y':
            return `~${value} years ago`;
        default:
            return String(value);
    }
}

export function format(format: string, date: Date | undefined): string {
    if (date === undefined) {
        return '';
    }

    let result = '';
    for (let i = 0; i < format.length; i++) {
        const char = format[i];
        if (char === 'Y') {
            result += date.getFullYear();
        } else if (char === 'm') {
            result += (date.getMonth() + 1).toString().padStart(2, '0');
        } else if (char === 'd') {
            result += date.getDate().toString().padStart(2, '0');
        } else if (char === 'H') {
            result += date.getHours();
        } else if (char === 'i') {
            result += date.getMinutes().toString().padStart(2, '0');
        } else if (char === 's') {
            result += date.getSeconds().toString().padStart(2, '0');
        } else if (char === 'u') {
            result += date.getMilliseconds().toString().padStart(3, '0');
        } else {
            result += char;
        }
    }

    return result;
}

/**
 * Get the day of the week, offset as monday as first day of the week
 */
export function getDayOfWeek(date: Date): number {
    return (date.getDay() + 6) % 7;
}

export function getFirstDayOfWeek(date: Date): Date {
    const firstDayOfWeek = new Date(date);
    firstDayOfWeek.setHours(12, 0, 0, 0);
    // sunday
    if (date.getDay() === 0) {
        firstDayOfWeek.setDate(date.getDate() - 6);
    } else {
        firstDayOfWeek.setDate(date.getDate() - date.getDay() + 1);
    }
    return firstDayOfWeek;
}

export function getFirstDayOfMonth(date: Date): Date {
    return new Date(date.getFullYear(), date.getMonth(), 1, 12, 0, 0, 0);
}

export function getMonthCalendarDates(monthDate: Date | undefined): Date[] {
    monthDate ??= new Date();
    const firstDayOfMonth = getFirstDayOfMonth(monthDate);
    const firstDayOfWeek  = getFirstDayOfWeek(firstDayOfMonth);
    const days            = getDaysInMonth(monthDate) + getDayOfWeek(firstDayOfMonth) - getDayOfWeek(firstDayOfWeek);
    const calendarDays    = days <= 35 ? 35 : 42;

    const dates: Date[] = [];
    for (let i = 0; i < calendarDays; i++) {
        const nextDate = new Date(firstDayOfWeek);
        nextDate.setDate(firstDayOfWeek.getDate() + i);
        dates.push(nextDate);
    }
    return dates;
}

export function getDaysInMonth(date: Date): number {
    return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
}

export function isSameDay(date1: Date | undefined, date2: Date | undefined): boolean {
    if (date1 === undefined || date2 === undefined) {
        return false;
    }
    return date1.getFullYear() === date2.getFullYear() && date1.getMonth() === date2.getMonth() && date1.getDate() === date2.getDate();
}

export function isSameMonth(date1: Date | undefined, date2: Date | undefined): boolean {
    if (date1 === undefined || date2 === undefined) {
        return false;
    }
    return date1.getFullYear() === date2.getFullYear() && date1.getMonth() === date2.getMonth();
}

export function getHours(): Date[] {
    const date  = new Date().setHours(0, 0, 0, 0);
    const hours = [];
    for (let i = 0; i < 24 * 4; i++) {
        hours.push(new Date(date + i * 15 * 60 * 1000));
    }
    return hours;
}

export function getMonths(): string[] {
    return [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];
}

export function setDayOfTheYear(date: Date, year: number, month: number, day: number): Date {
    const newDate = new Date(date);
    newDate.setFullYear(year);
    newDate.setMonth(month);
    newDate.setDate(day);
    return newDate;
}
