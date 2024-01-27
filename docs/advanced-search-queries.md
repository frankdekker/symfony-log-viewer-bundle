# Searching logs

The search query allows for more fine-grained control over the search results. The following operators are supported:

| Operator                             | Short | Description                                                                     |
|--------------------------------------|-------|---------------------------------------------------------------------------------|
| `before:<date>`,`before:"<date>"`    | `b`   | Show all logs messages that occur before the specified date.                    |
| `after:<date>`,`after:"<date>"`      | `a`   | Show all logs messages that occur after the specified date.                     |
| `exclude:<word>`,`exclude:"<words>"` | `-`   | Exclude the specific sentence from the results. Can be specified multiple times |

## Example

Search all log entries between `2020-01-01` and `2020-01-31`, excluding all entries that contain the word `"Controller"` and must
include `"Exception"`.

```text
before:2020-01-31 after:2020-01-01 exclude:Controller "Failed to read"
```

### In shorthand

```text
b:2020-01-31 a:2020-01-01 -:Controller "Failed to read"
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
