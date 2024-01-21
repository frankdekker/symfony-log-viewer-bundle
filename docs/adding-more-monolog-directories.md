## Add more monolog directories

If you want to add more directories or more log files to the default monolog configuration, you can do it by adding 
changing the finder configuration `fd_log_viewer.log_files.monolog.finder`:

```yaml
# config/packages/fd_log_viewer.yaml
fd_log_viewer:
  log_files:
    monolog:
      finder:
        in: "directory1,directory2,directory3"
        name: "*.log,*.txt"
```

### `in`
This specifies the `finder->in(...)` parameter for which directories will be scanned.

Examples:
- `"%kernel.logs_dir%"` - this is the default value, only files in the `var/log` directory will be scanned.
- `"%kernel.logs_dir%/**/` - this will scan all subdirectories of the `var/log` directory.
- `"%kernel.logs_dir%/*/log/"` - this will scan all subdirectories of the `var/log` directory that have a `log` sub-subdirectory.

### `name`
This specifies the `finder->name(...)` parameter for which files will be scanned.
