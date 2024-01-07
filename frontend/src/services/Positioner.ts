export function setRelativeTo(parentEl: HTMLElement, targetEl: HTMLElement) {
    // position bottom right
    targetEl.style.left = (parentEl.offsetWidth - targetEl.offsetWidth) + 'px';
    targetEl.style.top  = parentEl.offsetHeight + 'px';

    // check if target is outside of viewport
    if (targetEl.getBoundingClientRect().bottom > window.innerHeight) {
        // position top right
        targetEl.style.top = (-targetEl.offsetHeight) + 'px';
    }
}
