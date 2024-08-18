export function getRelativeDate(value: number, unit: string, floor: boolean): Date {
    // calculate start date
    const date = new Date();
    switch (unit) {
        case 's':
            date.setSeconds(date.getSeconds() - value);
            floor ? date.setMilliseconds(0) : null;
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

    const formattedDate = date.toLocaleDateString(undefined, {year: 'numeric', month: 'short', day: 'numeric'})
    const formattedTime = date.toLocaleTimeString(undefined, {hour: '2-digit', minute: '2-digit', second: '2-digit'})

    return `${formattedDate} @ ${formattedTime}`;
}
