export default interface DateSelection {
    date: Date;
    formatted: string;
    mode: 'absolute' | 'relative' | 'now';
}
