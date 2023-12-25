/**
 * Filter out all null values from an object.
 */
export function filter(value: {[key: K]: T | null}): {[key: K]: T} {
    return Object.fromEntries(Object.entries(value).filter(([_, value]) => value !== null));
}