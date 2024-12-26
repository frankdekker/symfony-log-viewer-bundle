## Upgrade migration guide

### Upgrade from 1.x to 2.x

- The `before:<date>` and `after:<date>` search terms were removed. Using date searches in combination with remote hosts is not compatible
  from v2 to v1. Upgrade all remote hosts to v2 before upgrading the local host to v2. In v3 the date search terms will be removed.
