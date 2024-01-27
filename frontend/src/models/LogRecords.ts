import type LogRecord from '@/models/LogRecord';
import type Paginator from '@/models/Paginator';
import type Performance from '@/models/Performance';

export default interface LogRecords {
    logs: LogRecord[];
    paginator: Paginator | null;
    performance?: Performance;
}
