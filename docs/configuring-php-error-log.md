# Configuring PHP error logs

To add php error logs to the Log Viewer, add the following to your `config/packages/fd_log_viewer.yaml` file:

```yaml
fd_log_viewer:
    log_files:
        error-log:
            type: php-error-log
            name: PHP error log
            finder:
                in: "/var/log/php/"
                name: "error.log"
```
_Change the path to your php error log path_

## Error logs

The `log_message_pattern`-regex that is used for the **error_log** is:

```regex
/^\[(?<date>.*?)\] (?<message>.*?)$/
```

The fields `date` and `message` mandatory. A pattern to add the `severity`, `channel`, `context` and `extra` is optional.
