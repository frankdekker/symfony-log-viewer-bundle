export default class Repeater {
    private intervalId: number | undefined = undefined;

    /**
     * @param interval in milliseconds
     * @param callback
     */
    constructor(private readonly interval: number, private readonly callback: () => void) {
    }

    public start(value?: boolean): void {
        if (value === false) {
            this.stop();
            return;
        }

        this.intervalId = window.setInterval(() => this.callback(), this.interval);
    }

    public stop(): void {
        clearInterval(this.intervalId);
    }
}
