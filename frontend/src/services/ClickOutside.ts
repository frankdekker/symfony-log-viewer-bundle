export default class ClickOutside {
    constructor(private readonly elements: HTMLElement[], private readonly onClickOutside: () => void) {
        this.onClick = this.onClick.bind(this);
    }

    public enable(enable: boolean = true): void {
        if (enable === false) {
            this.disable();
            return;
        }
        document.addEventListener('click', this.onClick);
    }

    public disable(): void {
        document.removeEventListener('click', this.onClick);
    }

    public addElement(element: HTMLElement): void {
        this.elements.push(element);
    }

    private onClick(event: MouseEvent): void {
        if (event.target instanceof HTMLElement === false || this.isOutside(event.target)) {
            this.onClickOutside();
        }
    }

    private isOutside(target: HTMLElement): boolean {
        for (const element of this.elements) {
            if (element === target || element.contains(target)) {
                return false;
            }
        }
        return true;
    }
}
