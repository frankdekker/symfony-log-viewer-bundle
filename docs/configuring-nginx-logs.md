# Configuring Nginx logs

To add nginx logs to the Log Viewer, add the following to your `config/packages/fd_log_viewer.yaml` file:

```yaml
fd_log_viewer:
    log_files:
        nginx-access:
            type: http-access
            name: Nginx access
            finder:
                in: "/var/log/nginx"
                name: "nginx.access.log"
        nginx-error:
            type: nginx-error
            name: Nginx error
            finder:
                in: "/var/log/nginx"
                name: "nginx.error.log"
```

## Access logs

The `log_message_pattern`-regex that is used for the **access logs** is:

```regex
/(?P<ip>\S+) (?P<identity>\S+) (?P<remote_user>\S+) \[(?P<date>[^\]]+)\] "(?P<method>\S+) (?P<path>\S+) (?P<http_version>\S+)" (?P<status_code>\S+) (?P<content_length>\S+) "(?P<referrer>[^"]*)" "(?P<user_agent>[^"]*)"/
```

The fields `date`, `method` and `path` are mandatory. The other fields are optional.

## Error logs

The `log_message_pattern`-regex that is used for the **error logs** is:

```regex
/^(?P<date>[\d+/ :]+) \[(?P<severity>.+)] .*?: (?P<message>.+?)(?:, client: (?P<ip>.+?))?(?:, server: (?P<server>.*?))?(?:, request: "?(?P<request>.+?)"?)?(?:, upstream: "?(?P<upstream>.+?)"?)?(?:, host: "?(?P<host>.+?)"?)?$/
```

The fields `date`, `severity`, and `message` are mandatory. The other fields are optional.
