## Configuration

### The default configuration

```yaml
log_files:
  monolog:
    # The type of log file: monolog, nginx, apache, or the service id of an implementation of `LogFileParserInterface`      
    type: monolog
    
    # The pretty name to show for these log files
    name: Log    
    
    finder:
      # The symfony/finder pattern to iterate through directories.  
      in: "%kernel.logs_dir%"
      # The symfony/finder pattern to filter files.
      name: "*.log"
      # Whether to ignore unreadable directories
      ignoreUnreadableDirs: true
      # Whether to follow symlinks
      followLinks: false

    # Whether to allow downloading of the log file      
    downloadable: false

    # The regex pattern for the start of a log line. Adds support for multiline log messages.
    start_of_line_pattern: '/^\[\d{4}-\d{2}-\d{2}[^]]*]\s+\S+\.\S+:/'
    
    # The regex pattern for a full log message which could include newlines.
    log_message_pattern: '/^\[(?P<date>[^\]]+)\]\s+(?P<channel>[^\.]+)\.(?P<severity>[^:]+):\s+(?P<message>.*)\s+(?P<context>[[{].*?[\]}])\s+(?P<extra>[[{].*?[\]}])\s+$/s'

    # The date format of how to present the date in the frontend.
    date_format: "Y-m-d H:i:s"
```
