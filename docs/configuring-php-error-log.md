# Configuring Apache2 logs

To add apache2 logs to the Log Viewer, add the following to your `config/packages/fd_log_viewer.yaml` file:

```yaml
fd_log_viewer:
    log_files:
        apache-access:
            type: php-error-log
            name: PHP error log
            finder:
                in: "/var/log/php/"
                name: "error.log"
```

## Access logs

The `log_message_pattern`-regex that is used for the **error_log** is:

```regex
/^\[(?<date>.*?)\] (?<message>.*?)$/
```

The fields `date` and `message` mandatory. A pattern to add the `severity`, `channel`, `context` and `extra` is optional.
