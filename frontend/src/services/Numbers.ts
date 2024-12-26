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
}
