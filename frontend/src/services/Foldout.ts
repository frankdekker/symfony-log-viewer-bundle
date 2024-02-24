import ClickOutside from '@/services/ClickOutside';
import type {Ref} from 'vue';
import {watch} from 'vue';

export default class {
    private clickOutside                    = new ClickOutside([], () => this.toggle(false));
    private button: HTMLElement | undefined = undefined;

    constructor(private readonly buttonRef: Ref<HTMLElement | undefined>, private readonly dropdownRef: Ref<HTMLElement | undefined>) {
    }

    public bind(): void {
        watch(this.buttonRef, () => {
            if (this.buttonRef.value instanceof HTMLElement) {
                this.buttonRef.value.addEventListener('click', () => {
                    console.log('click');
                });
            }
        });
    }

    public toggle(active: boolean | null = null): void {

    }
}
