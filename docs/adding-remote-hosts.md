## Add remote hosts

By default the log viewer only shows logs from the local server. If you want to add logs from a remote server, you can do so by adding a `hosts` 
section to the `fd_log_viewer` configuration.

### Configuration
Add one or more hosts to the `fd_log_viewer` configuration. Each host should have a unique key. Different types of authentication can be used.
```yaml
fd_log_viewer:
    hosts:
        # The default localhost. Can be removed to only have remote hosts. 
        localhost:
            name: Local
            host: null
            
        remote-public:
            name: Remote without authentication
            host: https://example.com/log-viewer
            
        # basic auth. Header: Authorization: Basic dXNlcjpwYXNz 
        remote-basic:
            name: Remote basic auth
            host: https://example.com/log-viewer
            auth:
                type: basic
                options:
                    username: user
                    password: pass
                    
        # bearer token. Header: Authorization: Bearer my-token                      
        remote-bearer:
            name: Remote bearer
            host: https://example.com/log-viewer
            auth:
                type: bearer
                options:
                    token: my-token
                    
        # custom header. Header: X-Private-Token: my-token                    
        remote-header:
            name: Remote custom header
            host: https://example.com/log-viewer
            auth:
                type: header
                options:
                    x-private-token: my-token
                    
        # custom authentication class                    
        remote-custom:
            name: Remote custom authenticator
            host: https://example.com/log-viewer
            auth:
                type: My\Class\That\Implements\AuthenticatorInterface
                options:
                    key: value   
```
