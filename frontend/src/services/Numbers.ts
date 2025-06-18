export default class Numbers {
    /**
     * Throws error when value is not valid number
     */
    public static parseInt(value: string): number {
        const number = Number.parseInt(value);
        if (isNaN(number)) {
            throw new Error('Invalid number value: ' + value);
        }
        return number;
    }

    public static numeric(value: unknown): boolean {
        if (typeof value === 'number') {
            return true;
        }
        if (typeof value === 'string') {
            return isNaN(Number(value)) === false;
        }
        return false;
    }
}
