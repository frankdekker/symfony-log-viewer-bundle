export default class Strings {

    public static trim(str: string, toRemove: string): string {
        while (str.startsWith(toRemove)) {
            str = str.substring(toRemove.length);
        }
        while (str.endsWith(toRemove)) {
            str = str.substring(0, str.length - toRemove.length);
        }

        return str;
    }

    public static nl2br(str: string): string {
        return str.replace(/\n/g, '<br/>');
    }

    public static escapeHtml(str: string): string {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
    }
}
