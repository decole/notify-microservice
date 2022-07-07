# Notify-microservice

## Как ставить:

> docker-compose.yaml.dist скопировать в файл docker-compose.yaml

```shell
make build
make up
make composer-install
make env
```

Добавить в `/etc/hosts`
```shell
127.0.0.1      notify.local
```

или обращаться http://localhost:85/


### Список энтрипоинтов: 
```markdown
http://localhost:85 - web заглушка
http://localhost:85/api/ - тестовый энтрипоинт
http://localhost:85/api/send - пример, пока GET запрос, создает запись в БД
```


### Спискок консольных команд:
```shell
# тестовая консольная команда
php bin/console cli:test

# тест работы сервиса нотификаций и репозитория нотификационных сообщений / исторических событий
php bin/console cli:test-repo
```

### Запуск воркеров на прослушивание очередей:
```shell
# работа в цикле. прослушивает очередь email
php bin/console rabbitmq:consumer email -vv
```