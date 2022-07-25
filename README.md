# Notify-microservice

## Как ставить:

> docker-compose.yaml.dist скопировать в файл docker-compose.yaml

Далее выполнить серию команд: 

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

[Настройка vkontakte очереди](docs/VK.md)


## Список энтрипоинтов: 
```markdown
http://localhost:85 - web заглушка
http://localhost:85/api/ - приветственный энтрипоинт
http://localhost:85/api/v1/send - POST запрос, создает задание на нотификацию
http://localhost:85/api/v1/check-status/{id} - проверка статуса отправки нотификации сервисом. {id} - id переданный в методе /api/v1/send
```

## Запуск воркеров на прослушивание очередей:
```shell
# работа в цикле. прослушивает очередь email
php bin/console rabbitmq:consumer email -vv

# работа в цикле. прослушивает очередь telegram
php bin/console rabbitmq:consumer telegram -vv

# работа в цикле. прослушивает очередь telegram
php bin/console rabbitmq:consumer vkontakte -vv

# очередь прослушивания исторических данных
php bin/console rabbitmq:consumer history -vv
```


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