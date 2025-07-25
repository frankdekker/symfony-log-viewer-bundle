# Configuration reference

## The default configuration

Out of the box, [Log viewer](../README.md) will have the following configuration

```yaml
fd_log_viewer:
    home_route: null

    log_files:
        monolog:
            type: monolog
            name: Monolog
            finder:
                in: "%kernel.logs_dir%"
                name: "*.log"
                depth: '== 0'
                ignoreUnreadableDirs: true
                followLinks: false
            downloadable: false
            deletable: false
            start_of_line_pattern: '/^\[\d{4}-\d{2}-\d{2}[^]]*]\s+\S+\.\S+:/'
            log_message_pattern: '/^\[(?P<date>[^\]]+)\]\s+(?P<channel>[^\.]+)\.(?P<severity>[^:]+):\s+(?P<message>.*)\s+(?P<context>(?:{.*?}|\[.*?]))\s+(?P<extra>(?:{.*?}|\[.*?]))\s+$/s'
            date_format: null

    hosts:
        localhost:
            name: Local
            host: null
```

## Configuration

### home_route

**type**: `string|null`. Default: `null`

The name of the route that will be used as url for the back button. If null the back button will redirect to `https://your-domain/`.
<br><br>

### log_files

**type**: `array<string, mixed>`

This entry allows you to add more log file directories to the Log Viewer. Each entry can contain multiple log files.
<br><br>

### log_files.type

**type**: `string` (`enum: monolog(.json)|http-access|apache-error|nginx-error|php-error-log`)

This is the type of log file that will be read.

- `monolog` is the default type and will read the default monolog log files.
- `monolog.json` will read the monolog log files that use `formatter: 'monolog.formatter.json'`.
- `http-access` will read the access log files of Apache and Nginx.
- `apache-error` will read the error log files of Apache.
- `nginx-error` will read the error log files of Nginx.
- `php-error-log` will read the PHP error log files.
  <br><br>

### log_files.name

**type**: `string`

This is the name that will be displayed in the Log Viewer as the top level folder name.
<br><br>

### log_files.finder

**type**: `array`

This is the configuration for the Symfony Finder component. See the [Symfony documentation](https://symfony.com/doc/current/components/finder.html)
for more information.
<br><br>

### log_files.finder.in

**type**: `string` (`comma-separated-string`)

This is the directory that will be searched for log files. Multiple directories can be specified separating them by a comma.

Example:

```text
"%kernel.logs_dir%,/var/log/nginx"
```

<br>

### log_files.finder.name

**type**: `string` (`comma-separated-string`)

This is the file pattern that will be searched for. Multiple patterns can be specified, separating them by a comma.
Example:

```text
*.log,*.txt
```

<br>

### log_files.finder.depth

**type**: `int|string|string[]|null`. Default: `'== 0'`

The maximum depth of directories to search for log files. If set to null all subdirectories will be added.

Example:

- `'== 0'` will only search in the specified directory.
- `'>= 0'` will search in the specified directory and all subdirectories.
- `['>= 0', '< 3]` will search in the specified directory and all subdirectories up to a depth of 2.
  <br><br>

### log_files.finder.ignoreUnreadableDirs

**type**: `boolean`. Default: `true`

Should unreadable directories by ignored.
<br><br>

### log_files.finder.followLinks

**type**: `boolean`. Default: `false`

Should symbolic links be followed.
<br><br>

### log_files.downloadable

**type**: `boolean`. Default: `false`

Should the log folders and files be downloadable.
<br><br>

### log_files.deletable

**type**: `boolean`. Default: `false`

Should the log folders and files be deletable.
<br><br>

### log_files.start_of_line_pattern

**type**: `string|null`.

As certain log files can contain multiple lines per log entry, this pattern is used to find the start of a log entry. Any lines not matching
the pattern will be appended to the line before.
The default monolog regex pattern is `/^\[\d{4}-\d{2}-\d{2}[^]]*]\s+\S+\.\S+:/` and matches:

```text
[2021-08-01 ...] app.INFO: ....
```

If set to `null`, every line in the log will be treated as an individual log entry.
<br><br>

### log_files.log_message_pattern

**type**: `string`.

This regex pattern is used to parse the log entry into the following named groups:

- `date`
- `channel`
- `severity`
- `message`
- `context`
- `extra`

The default monolog regex
pattern `/^\[(?P<date>[^\]]+)\]\s+(?P<channel>[^\.]+)\.(?P<severity>[^:]+):\s+(?P<message>.*)\s+(?P<context>(?:{.*?}|\[.*?]))\s+(?P<extra>(?:{.*?}|\[.*?]))\s+$/s`
matches:

```text
[2021-08-01T01-02-33+01:00] app.INFO: This is the log message {"context":"context"} {"extra":"extra"}\n
```

If you use a custom monolog format, adjust this pattern to your needs.
<br><br>

### log_files.date_format

**type**: `string`. Default: `null`

The date format to parse the date from the log entry. If set to `null`, the format will be guessed by `strtotime`.
<br><br>

### hosts

**type**: `array<string, mixed>`

This entry allows you to add more hosts to the log viewer

**Full example:**
```yaml
hosts:
    local:
        name: Local
        host: null
    remote:
        name: Remote
        host: https://example.com/log-viewer
        auth:
            type: basic
            options:
                username: user
                password: pass
```
<br>

### hosts.name

**type**: `string`

The display name of the host. Will be shown in the UI host selector.
<br><br>

### hosts.host

**type**: `string|null`

The url to the remote host log-viewer API. If null, the local host will be used. Must include the log-viewer route prefix.
Example: `host: https://example.com/log-viewer`

### hosts.auth

**type**: `array<string, mixed>`

For remote hosts, specifies the authentication method.
<br><br>

### hosts.auth.type

**type**: `string` (`enum: basic|bearer|header|class-string`)

The remote host authentication method:

- `basic`: Basic authentication. Requires `username` and `password` option.
- `bearer`: Bearer token authentication. Requires `token` option.
- `header`: Custom header authentication. The key-values in the options will be added to the request headers.
- `class-string`: Custom authentication. The class must implement `FD\LogViewerBundle\Authentication\AuthenticationInterface`.
<br><br>

### hosts.auth.options

**type**: `array<string, string>`
The options specific for the authentication method.
<br><br>
