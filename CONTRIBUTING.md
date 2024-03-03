# Contributing
 
Contributions are **welcome** and will be fully **credited**.
 
We accept contributions via Pull Requests on [Github](https://github.com/).
 
 
## Pull Requests
 
- **[PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** - The easiest way to apply the conventions is to install [PHP Code Sniffer](http://pear.php.net/package/PHP_CodeSniffer).
 
- **Add tests!** - Your patch won't be accepted if it doesn't have tests.
 
- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.
 
- **Consider our release cycle** - We try to follow [SemVer v2.0.0](http://semver.org/). Randomly breaking public APIs is not an option.
 
- **Create feature branches** - Don't ask us to pull from your master branch.
 
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
 
## Development

### Requirements
- [docker >= 20.10](https://docs.docker.com/engine/install/)
- [docker compose plugin](https://docs.docker.com/compose/install/linux/)

### Getting started
A minimal Symfony project has been setup under `/dev/`. To start development, in the root of the project run:

```bash
./start.sh [--port <nr>]
```

After the containers are up and running, the project is available at http://localhost:8888.

> **_NOTE:_**  the nodejs container will automatically rebuild the frontend assets and deploy them in the `/public/` directory
 
## Running tests and code style checks
 
``` bash
composer run check
composer run test
cd frontend && npm run lint
```
