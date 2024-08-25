import {describe, expect, test} from 'vitest';
import {getFirstDayOfMonth} from '@/services/Dates.ts';

describe('Dates', () => {
    describe('getFirstDayOfMonth', () => {
        test.each(
            [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        )('should determine first day of month', (month) => {
            const date     = new Date(2021, month, 15, 12, 0, 0, 0);
            const expected = new Date(2021, month, 1, 12, 0, 0, 0);
            expect(getFirstDayOfMonth(date)).toEqual(expected);
        });

    });
});
