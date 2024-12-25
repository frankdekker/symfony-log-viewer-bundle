export default class ParameterBag {
    constructor(private parameters: { [key: string]: unknown } = {}) {
    }

    public set<T = unknown>(key: string, value: T | null, defaultVal: T | null = null): ParameterBag {
        if (value === defaultVal) {
            value = null;
        }

        if (value !== null && value !== undefined && value !== '') {
            this.parameters[key] = String(value);
        }
        return this;
    }

    public all(): { [key: string]: unknown } {
        return this.parameters;
    }

    public toString(): string {
        return new URLSearchParams(<Record<string, string>>this.parameters).toString();
    }
}
