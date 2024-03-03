export default class ParameterBag {
    private parameters: { [key: string]: any } = {};

    public set<T = any>(key: string, value: T | null, defaultVal: T | null = null): ParameterBag {
        if (value === defaultVal) {
            value = null;
        }

        if (value !== null) {
            this.parameters[key] = String(value);
        }
        return this;
    }

    public all(): { [key: string]: any } {
        return this.parameters;
    }

    public toString(): string {
        return new URLSearchParams(this.parameters).toString();
    }
}
