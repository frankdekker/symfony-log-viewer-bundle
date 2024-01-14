export function setRelativeTo(parentEl: HTMLElement, targetEl: HTMLElement, alignment: 'left' | 'right' = 'right') {
    if (alignment === 'right') {
        // position bottom right
        targetEl.style.left = (parentEl.offsetWidth - targetEl.offsetWidth) + 'px';
    } else {
        // position bottom left
        targetEl.style.left = '0px';
    }
    targetEl.style.top  = parentEl.offsetHeight + 'px';

    // check if target is outside of viewport
    if (targetEl.getBoundingClientRect().bottom > window.innerHeight) {
        // position top right
        targetEl.style.top = (-targetEl.offsetHeight) + 'px';
    }
}
