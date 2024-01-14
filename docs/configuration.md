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
```

## Configuration reference

### log_files

This entry allows you to add more log file directories to the Log Viewer. Each entry can contain multiple log files.

### log_files.type

This is the type of log file that will be read. Currently only `monolog` is supported.

### log_files.name

This is the name that will be displayed in the Log Viewer as the top level folder name.

### log_files.finder

This is the configuration for the Symfony Finder component. See the [Symfony documentation](https://symfony.com/doc/current/components/finder.html) 
for more information.

### log_files.finder.in

This is the directory that will be searched for log files. Multiple directories can be specified separating them by a comma.

Example:
```text
"%kernel.logs_dir%,/var/log/nginx"
```

### log_files.finder.name

This is the file pattern that will be searched for. Multiple patterns can be specified, separating them by a comma.
Example:
```text
*.log,*.txt
```

### log_files.finder.ignoreUnreadableDirs

Should unreadable directories by ignored. Default: `true`

### log_files.finder.followLinks

Should symbolic links be followed. Default: `false`

### log_files.downloadable

Should the log folders and files be downloadable. Default: `false`

### log_files.deletable

Should the log folders and files be deletable. Default: `false`

### log_files.start_of_line_pattern
