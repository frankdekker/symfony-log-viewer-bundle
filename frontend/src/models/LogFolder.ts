import type LogFile from '@/models/LogFile';

export default interface LogFolder {
    identifier: string;
    path: string;
    download_url: string;
    can_download: boolean;
    files: LogFile[];
}
