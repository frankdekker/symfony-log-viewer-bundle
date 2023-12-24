import type LogRecord from '@/models/LogRecord';
import type Paginator from '@/models/Paginator';

export default interface LogRecords {
    file: string;
    levels: {
        choices: {[key: string]: string};
        selected: string[];
    };
    channels: {
        choices: {[key: string]: string};
        selected: string[];
    };
    logs: LogRecord[];
    paginator: Paginator | null;
    performance?: {
        memoryUsage: string;
        requestTime: string;
        version: string;
    }
}
