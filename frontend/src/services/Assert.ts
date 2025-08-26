export default class Assert {
    public static string(value: unknown): string {
        if (typeof value !== 'string' && value instanceof String === false) {
            throw new Error('Value is not a string: ' + typeof value)
        }
        return String(value);
    }
}
