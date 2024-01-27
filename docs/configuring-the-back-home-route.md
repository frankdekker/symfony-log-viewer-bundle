## Configuring the home route

By default, the back button will redirect to the `/` route. You can change this by setting the `home_route` option in the configuration:

```yaml
# config/packages/fd_log_viewer.yaml
fd_log_viewer:
  home_route: App\Controller\HomeController
```
