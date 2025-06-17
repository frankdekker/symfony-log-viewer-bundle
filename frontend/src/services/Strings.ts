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
}
