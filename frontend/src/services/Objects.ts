/**
 * Filter out all null values from an object.
 */
export function filter(value: { [key: string]: number | string | null }): { [key: string]: number | string } {
    return <{ [key: string]: number | string }>Object.fromEntries(Object.entries(value).filter(entry => entry[1] !== null));
}
