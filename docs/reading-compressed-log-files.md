## Reading compressed log files

The log viewer supports reading compressed log files, only `.gz` files are supported.

### Requirements

The PHP `ext-zlib` extension must be installed and enabled:
```bash
php -m | grep zlib
```

### Configuration

Add the `.gz` pattern in the `finder` configuration:

```yaml
fd_log_viewer:
  log_files:
    monolog:
      type: monolog
      name: Monolog
      finder:
        in: "%kernel.logs_dir%"
        name: ["*.log", "*.log.gz"]
```

### Limitations

- Compressed log files can only be read in **oldest first** order. The "Newest First" sort direction is not available when a compressed file is selected.
- Seeking within a `.gz` file requires decompressing from the beginning, so pagination on large compressed files may be slower than on regular log files.
