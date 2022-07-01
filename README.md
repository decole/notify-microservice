# Notify-microservice

## Как ставить:

> docker-compose.yaml.dist скопировать в файл docker-compose.yaml

```shell
make build
make up
make composer-install
make env
```

Добавить в /etc/hosts 
```shell
127.0.0.1      notify.local
```

или обращаться http://localhost:85/