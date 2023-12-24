/**
 * Convert value to null if it is equal to defaultValue
 */
export function nullify<T>(value: T, defaultValue: T): T {
    return value === defaultValue ? null : value;
}
