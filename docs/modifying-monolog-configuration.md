## Modifying the default Monolog configuration

Open or create the file `config/packages/fd_log_viewer.yaml`

**Set downloadable or deletable:**
```yaml
fd_log_viewer:
    log_files:
        monolog:
            downloadable: true
            deletable: true
```

See also: [Adding more monolog directories](adding-more-monolog-directories.md).
