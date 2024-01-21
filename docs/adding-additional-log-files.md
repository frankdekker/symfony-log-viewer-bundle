## Add additional log files

Open or create the file `config/packages/fd_log_viewer.yaml`

**Add a section to `log_files` with a unique name like `example`:**
```yaml
fd_log_viewer:
  log_files:
    example:
      type: monolog
      name: Example
      finder:
        in: "/app/var/log"
        name: "*.log"        
```
