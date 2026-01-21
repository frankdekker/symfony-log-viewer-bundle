export default interface LogFile {
    identifier: string;
    name: string;
    size_formatted: string;
    open: boolean;
    can_download: boolean;
    can_delete: boolean;
}
