import type LogRecord from '@/models/LogRecord';
import type Paginator from '@/models/Paginator';
import type Performance from '@/models/Performance';

export default interface LogRecords {
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
    performance?: Performance;
}
