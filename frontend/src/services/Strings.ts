export function trim(str: string, toRemove: string): string {
    while (str.startsWith(toRemove)) {
        str = str.substring(toRemove.length);
    }
    while (str.endsWith(toRemove)) {
        str = str.substring(0, str.length - toRemove.length);
    }

    return str;
}

export function nl2br(str: string): string {
    return str.replace(/\n/g, '<br/>');
}

export function escapeHtml(str: string): string {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
}
