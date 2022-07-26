# Notify-microservice


### Проектная работа по курсу **[OTUS](https://otus.ru/) [PHP Developer. Professional](https://otus.ru/lessons/razrabotchik-php/)**

![gif](docs/kek.gif)


## Как ставить:

> файл docker-compose.yaml.dist скопировать в файл docker-compose.yaml

Далее выполнить серию команд: 

```shell
# На Linux установать make - sudo apt install - для последующе работы. 
# Либо если другая у вас ОС, смотрите в Makefile - файле в корне репозитория. каждая фраза - цепочка вызовов linux команд
# соберет необходимые docker образа
make build 
# скопирует необходимые файлы из шаблонов
make env
# поднимет проект
make up
# composer установит необходимые пакеты для работы приложения
make composer-install
#применятся миграции к БД
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

[Настройка SMS очереди](docs/SMS.md) - заглушка. Должна быть рабочей, ибо логика отправки бралась из окументации


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

# работа в цикле. прослушивает очередь vkontakte
php bin/console rabbitmq:consumer vkontakte -vv

# работа в цикле. прослушивает очередь sms
php bin/console rabbitmq:consumer sms -vv

# очередь прослушивания исторических данных
php bin/console rabbitmq:consumer history -vv
```

# Для CI/CD напоминания:
Для тестов

```shell
# create the test database
php bin/console --env=test doctrine:database:create

# create the tables/columns in the test database
php bin/console --env=test doctrine:schema:create

# apply migrates for test DB

php bin/console doctrine:migrations:migrate --env test
```

Для деплоя на прод и стейдж перед каждым стартом эксплуатации надо выполнять эту команду для 
генерации тем очередей RabbitMQ

```shell
php bin/console rabbitmq:setup-fabric
```