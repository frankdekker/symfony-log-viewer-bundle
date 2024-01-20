# Configuration

## The default configuration


To modify the existing configuration, create a file named `fd_log_viewer.yaml` in the `config/packages` directory of your project.
```yaml
# /config/packages/fd_log_viewer.yaml
fd_log_viewer:
  log_files:
    monolog:     
      type: monolog
      name: Monolog
      finder: 
        in: "%kernel.logs_dir%"
        name: "*.log"
        ignoreUnreadableDirs: true
        followLinks: false
      downloadable: false
      deletable: false
      start_of_line_pattern: '/^\[\d{4}-\d{2}-\d{2}[^]]*]\s+\S+\.\S+:/'
      log_message_pattern: '/^\[(?P<date>[^\]]+)\]\s+(?P<channel>[^\.]+)\.(?P<severity>[^:]+):\s+(?P<message>.*)\s+(?P<context>[[{].*?[\]}])\s+(?P<extra>[[{].*?[\]}])\s+$/s'
      date_format: "Y-m-d H:i:s"
  enable_default_monolog: true
```

## Configuration reference

### log_files

This entry allows you to add more log file directories to the Log Viewer. Each entry can contain multiple log files.

### log_files.type <small>`monolog`</small>

This is the type of log file that will be read. Currently only `monolog` is supported.

### log_files.name <small>`string`</small>

This is the name that will be displayed in the Log Viewer as the top level folder name.

### log_files.finder <small>`array`</small>

This is the configuration for the Symfony Finder component. See the [Symfony documentation](https://symfony.com/doc/current/components/finder.html) 
for more information.

### log_files.finder.in <small>`comma-separated-string`</small>

This is the directory that will be searched for log files. Multiple directories can be specified separating them by a comma.

Example:
```text
"%kernel.logs_dir%,/var/log/nginx"
```

### log_files.finder.name <small>`comma-separated-string`</small>

This is the file pattern that will be searched for. Multiple patterns can be specified, separating them by a comma.
Example:
```text
*.log,*.txt
```

### log_files.finder.ignoreUnreadableDirs <small>`boolean`</small>

Should unreadable directories by ignored. Default: `true`


### log_files.finder.followLinks <small>`boolean`</small>

Should symbolic links be followed. Default: `false`


### log_files.downloadable <small>`boolean`</small>

Should the log folders and files be downloadable. Default: `false`


### log_files.deletable <small>`boolean`</small>

Should the log folders and files be deletable. Default: `false`


### log_files.start_of_line_pattern <small>`string`</small>

As monolog files can contain multiple lines per log entry, this pattern is used to find the start of a log entry. Any lines not matching
the pattern will be appended to the line before.
The default regex pattern is `/^\[\d{4}-\d{2}-\d{2}[^]]*]\s+\S+\.\S+:/` and matches:
```text
[2021-08-01 ...] app.INFO: ....
```

If set to `null`, every line in the log will be treated as an individual log entry.


### log_files.log_message_pattern <small>`string`</small>

This regex pattern is used to parse the log entry into the following named groups:
- `date`
- `channel`
- `severity`
- `message`
- `context`
- `extra`

The default regex pattern `/^\[(?P<date>[^\]]+)\]\s+(?P<channel>[^\.]+)\.(?P<severity>[^:]+):\s+(?P<message>.*)\s+(?P<context>[[{].*?[\]}])\s+(?P<extra>[[{].*?[\]}])\s+$/s` matches:
```text
[2021-08-01T01-02-33+01:00] app.INFO: This is the log message {"context":"context"} {"extra":"extra"}\n
```

If you use a custom monolog format, adjust this pattern to your needs.


### log_files.date_format <small>`string`</small>

This is the date format that will be used to format the date in frontend. Default: `Y-m-d H:i:s`


### enable_default_monolog <small>`boolean`</small>

Should the default monolog configuration be enabled? Default: `true`
