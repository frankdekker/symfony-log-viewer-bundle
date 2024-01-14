export default class Arrays {
    public static split(value: string, separator: string): string[] {
        if (value === '') {
            return [];
        }
        return value.split(separator).map((item) => item.trim());
    }
}
