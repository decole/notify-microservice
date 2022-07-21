# Notify-microservice

## Как ставить:

> docker-compose.yaml.dist скопировать в файл docker-compose.yaml

```shell
make build
make up
make composer-install
make env
make migration
```


Добавить в `/etc/hosts`
```shell
127.0.0.1      notify.local
```

или по URL: http://localhost:85/

В .env определен порт 85, менять по вкусу

## Настройка систем очередей 

[Настройка email очереди](docs/EMAIL.md)

[Настройка telegram очереди](docs/TELEGRAM.md)


## Список энтрипоинтов: 
```markdown
http://localhost:85 - web заглушка
http://localhost:85/api/ - приветственный энтрипоинт
http://localhost:85/api/v1/send - POST запрос, создает задание на нотификацию
http://localhost:85/api/v1/check-status/{id} - проверка статуса отправки нотификации сервисом. {id} - id переданный в методе /api/v1/send
```


## Спискок консольных команд:
```shell
# тестовая консольная команда
php bin/console cli:test

# тест работы сервиса нотификаций и репозитория нотификационных сообщений / исторических событий
php bin/console cli:test-repo
```

## Запуск воркеров на прослушивание очередей:
```shell
# работа в цикле. прослушивает очередь email
php bin/console rabbitmq:consumer email -vv

# работа в цикле. прослушивает очередь telegram
php bin/console rabbitmq:consumer telegram -vv

# очередь прослушивания исторических данных
php bin/console rabbitmq:consumer history -vv
```

## Нстройка отправки email сообщений

0. Скопировать **.env.local.example** в **.env.local**
1. Сконфигурировать **.env.local** под свои параметры почты
2. !!! в **.env.local** сконфигурировать все параметры под себя (особено **MAILER_DSN** и **EMAIL_FROM**)

> Яндекс почта требует, чтобы отправитель(from) был тем же, 
> что и почтовый акаунт с которого отправляете сообщения. 
> Т.е. в **.env.local** EMAIL_FROM указать тот же имейл, например 
> я сконфигурировал MAILER_DSN под почту **example@yandex.ru**, тогда 
> у меня **EMAIL_FROM=example@yandex.ru**


# По тестам

- модуль Doctrine2 для создание сущностей доктрины не пользуя БД https://github.com/Codeception/module-doctrine2/blob/master/tests/unit/Codeception/Module/Doctrine2Test.php
- DataFactory позволяет легко генерировать и создавать тестовые данные с помощью FactoryMuffin. DataFactory использует ORM вашего приложения для определения, сохранения и очистки данных. Таким образом, следует использовать с модулями ORM или Framework.  https://codeception.com/docs/modules/DataFactory


# Для CI/CD напоминания:
Для тестов

```shell
# create the test database
php bin/console --env=test doctrine:database:create

# create the tables/columns in the test database
php bin/console --env=test doctrine:schema:create



php bin/console doctrine:migrations:migrate --env test
```


Для деплоя на прод и стейдж перед каждым стартом эксплуатации надо выполнять эту команду для 
генерации тем очередей

```shell
php bin/console rabbitmq:setup-fabric
```