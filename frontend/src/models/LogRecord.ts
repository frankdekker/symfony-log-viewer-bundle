export default interface LogRecord {
    datetime: string;
    level_name: string;
    level_class: string;
    channel: string;
    text: string;
    context: { [key: string]: unknown; }|string;
    extra: { [key: string]: unknown; }|string;
    context_line?: boolean;
}
