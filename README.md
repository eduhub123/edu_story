

# lesson service for optimize load
REST API in the Lumen (micro-framework by Laravel) with Docker.


### Docker Setup
- [Docker for Mac](https://docs.docker.com/docker-for-mac/)
- [Docker for Linux](https://docs.docker.com/engine/installation/linux/)
- [Docker for Windows](https://docs.docker.com/docker-for-windows/)

### Docker Basic
Run the command bellow at the root of the project.

```ssh

$ cd ./service; composer install
$ rename file edu_award/service/env_example to edu_award/service/.env
$ docker-compose up --build -d
```

Access the app on http://localhost:9112/

### Contribute
Submit a Pull Request!
