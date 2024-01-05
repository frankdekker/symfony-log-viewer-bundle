/**
 * Filter out all null values from an object.
 */
export function filter<K, T>(value: {[key: K]: T | null}): {[key: K]: T} {
    return Object.fromEntries(Object.entries(value).filter(entry => entry[1] !== null));
}
