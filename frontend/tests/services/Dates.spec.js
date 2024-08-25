import {describe, expect, test} from 'vitest';
import {getFirstDayOfMonth, getDaysInMonth} from '@/services/Dates.ts';

describe('Dates', () => {
    describe('getFirstDayOfMonth', () => {
        test.each(
            [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        )('should determine first day of month %i', (month) => {
            const date     = new Date(2021, month, 15, 12, 0, 0, 0);
            const expected = new Date(2021, month, 1, 12, 0, 0, 0);
            expect(getFirstDayOfMonth(date)).toEqual(expected);
        });
    });

    describe('getDaysInMonth', () => {
        test.each(
            [[0, 31], [1, 28], [2, 31], [3, 30], [4, 31], [5, 30], [6, 31], [7, 31], [8, 30], [9, 31], [10, 30], [11, 31]]
        )('should days in the month (%i/%i)', (month, days) => {
            const date     = new Date(2021, month, 1, 12, 0, 0, 0);
            expect(getDaysInMonth(date)).toEqual(days);
        });
    });
});
