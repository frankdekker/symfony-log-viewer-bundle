import {describe, expect, test} from 'vitest';
import {getDaysInMonth, getFirstDayOfMonth, getFirstDayOfWeek, getMonthCalendarDates} from '../../src/services/Dates';

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
            const date = new Date(2021, month, 1, 12, 0, 0, 0);
            expect(getDaysInMonth(date)).toEqual(days);
        });
    });

    describe('getFirstDayOfWeek', () => {
        test.each(
            [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        )('should days in the month (%i)', (month) => {
            const date = getFirstDayOfWeek(new Date(2024, month, 1, 12, 0, 0, 0));
            expect(date.getDay()).toEqual(1);
        });

        test('monday as first day of the month (1-jul-2024)', () => {
            const date = getFirstDayOfWeek(new Date(2024, 6, 1, 12, 0, 0, 0));
            expect(date).toEqual(new Date(2024, 6, 1, 12, 0, 0, 0));
        });

        test('sunday as first day of the month (1-sep-2024)', () => {
            const date = getFirstDayOfWeek(new Date(2024, 8, 1, 12, 0, 0, 0));
            expect(date).toEqual(new Date(2024, 7, 26, 12, 0, 0, 0));
        });
    });

    describe('getMonthCalendarDates', () => {
        test('get days in the month (august 2024)', () => {
            const date  = new Date(2024, 7, 15, 12, 0, 0, 0);
            const dates = getMonthCalendarDates(date);
            expect(dates.length).toEqual(35);
            // 29-jul-2024
            expect(dates[0]).toEqual(new Date(2024, 6, 29, 12, 0, 0, 0));
            // 2-sep-2024
            expect(dates[34]).toEqual(new Date(2024, 8, 1, 12, 0, 0, 0));
        });
        test('get days in the month (september 2024)', () => {
            const date  = new Date(2024, 8, 15, 12, 0, 0, 0);
            const dates = getMonthCalendarDates(date);
            expect(dates.length).toEqual(42);
            // 26-aug-2024
            expect(dates[0]).toEqual(new Date(2024, 7, 26, 12, 0, 0, 0));
            // 6-oct-2024
            expect(dates[41]).toEqual(new Date(2024, 9, 6, 12, 0, 0, 0));
        });
    });
});
