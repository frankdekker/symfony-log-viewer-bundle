# Configuring Apache2 logs

To add apache2 logs to the Log Viewer, add the following to your `config/packages/fd_log_viewer.yaml` file:

```yaml
fd_log_viewer:
    log_files:
        apache-access:
            type: http-access
            name: Apache2 access
            finder:
                in: "/var/log/apache2"
                name: "access.log"
        apache-error:
            type: apache-error
            name: Apache2 error
            finder:
                in: "/var/log/apache2"
                name: "error.log"
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
/^\[(?<date>.*?)\] \[(?:(?<module>.*?):)?(?<severity>.*?)\] \[pid\s(?<pid>\d*)\](?: (?<error_status>[^\]]*?))?(?: \[client (?<ip>.*):(?<port>\d+)\]) (?<message>.*?)(?:, referer: (?<referer>\S*?))?$/
```

The fields `date`, `severity`, and `message` are mandatory. The other fields are optional.
