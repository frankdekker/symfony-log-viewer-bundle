export default class Objects {
    public static isObject(value: unknown): value is {[key: string]: unknown} {
        return value !== null && typeof value === 'object' && Array.isArray(value) === false;
    }
}
