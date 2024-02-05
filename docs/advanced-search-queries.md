# Searching logs

The search query allows for more fine-grained control over the search results. The following operators are supported:

| Operator                                    | Short | Description                                                                     |
|---------------------------------------------|-------|---------------------------------------------------------------------------------|
| `before:<date>`,`before:"<date>"`           | `b`   | Show all logs messages that occur before the specified date.                    |
| `after:<date>`,`after:"<date>"`             | `a`   | Show all logs messages that occur after the specified date.                     |
| `severity:<pipe-separated-string>`          | `s`   | Show all logs messages that match the given severity/severities.                |
| `channel:<pipe-separated-string>`           | `c`   | Show all logs messages that match the given channel(s).                         |
| `after:<date>`,`after:"<date>"`             | `a`   | Show all logs messages that occur after the specified date.                     |
| `exclude:<word>`,`exclude:"<words>"`        | `-`   | Exclude the specific sentence from the results. Can be specified multiple times |
| `context:<string>`, `context:<key>=<value>` |       | Show all logs messages that match the given context.                            |
| `extra:<string>`, `extra:<key>=<value>`     |       | Show all logs messages that match the given extra.                              |

## Example

Search all log entries between `2020-01-01` and `2020-01-31`, for severity `warning` or `error`, in channel `app`
excluding all entries that contain the word `"Controller"` and must include `"Exception"`.

```text
before:2020-01-31 after:2020-01-01 severity:warning|error channel:app exclude:Controller "Failed to read"
```

### In shorthand

```text
b:2020-01-31 a:2020-01-01 s:warning|error c:app -:Controller "Failed to read"
```

### Multiple exclusions

```text
before:2020-01-31 after:2020-01-01 exclude:Controller exclude:404
```

### Handling whitespace

If you want to search for a sentence that contains whitespace, you can use double quotes to indicate that the words should be treated as a single
word.

```text
before:"2020-01-31 23:59:59" after:"2020-01-01 00:00:00" exclude:"new IndexController" "Failed to read"
```

### Searching context or extra

Matches if at least one of the array values contains the given string:
```text
context:foo
extra:bar
```

### Searching specific array key-values in context or extra:
```text 
context:userId=123
context:request.uri=/api/v1/users
extra:trace_id=12345ab
```
