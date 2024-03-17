## Disable the default monolog configuration

If you want to disable the default monolog configuration, overwrite `log_files` with your own configuration. For example:
```yaml
# config/packages/fd_log_viewer.yaml
fd_log_viewer:
    log_files:
        apache-access:
            type: http-access
            name: Apache2 access
            finder:
                in: "/var/log/apache2"
                name: "access.log"
```
