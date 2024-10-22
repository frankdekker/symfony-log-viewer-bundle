export default interface LogRecord {
    datetime: string;
    level_name: string;
    level_class: string;
    channel: string;
    text: string;
    context: object|string;
    extra: object|string;
}
