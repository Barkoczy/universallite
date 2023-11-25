# Universal.lite

Universal.lite is a PHP kit that helps you quickly write simple yet powerful web applications and APIs.

### Docker

Docker must be installed on your machina. For windows use download from this link https://www.docker.com/get-started Docker Desktop version 
and for otherwise os version see more info on website https://www.docker.com.

#### Bring up local development enviroment via Docker

Create and edit enviroment config file:

```shell
composer run-script create-env-config-file
```

#### Run

Use command line (powershell for windows, git bash and otherwise),
go to project folder and run command.

Run like deamon (automatic run after start os) - parameter [ -d ]

```bash
docker-compose up -d
```

Run in console

```bash
docker-compose up
```

Install composer packages:

```shell
docker-compose exec app composer install
```

Then you should be able to access the application on:

```shell
http://localhost:8000
```
