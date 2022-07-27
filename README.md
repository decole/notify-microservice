# Notify-microservice


### Проектная работа по курсу **[OTUS](https://otus.ru/) [PHP Developer. Professional](https://otus.ru/lessons/razrabotchik-php/)**

![gif](docs/kek.gif)


## Postman collection (документация по API):

Импортируйте себе [Postman коллекцию](docs/REST_API_MICROSERVICE.postman_collection.json), чтобы видеть у себя все энтрипоинты с примерами данных.


## Как ставить:
1. Файл **docker-compose.yaml.dist** скопировать в файл **docker-compose.yaml**
2. Далее выполнить серию команд: 

```shell
# На Linux установать make - sudo apt install - для последующе работы. 
# Либо если другая у вас ОС, смотрите в Makefile - файле в корне репозитория. 
# Каждая фраза - цепочка вызовов linux команд
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


Добавить в `/etc/hosts` следующую строчку: (чтобы приложение было доступно из http://notify.local:85/api/)
```shell
127.0.0.1      notify.local
```

или пинайте API по URL: http://localhost:85/api/

В .env определен порт 85, менять по вкусу.


## Пример настроек docker контейнеров для каждой очереди:
В **docker-compose.yaml.dist** есть закоментированные примеры docker контейнеров запускающих конкретные очереди.
Для правильной работы, нужны контейнеры с очередями нотификаций и обязательно минимум 1 контейнер **worker-history** для 
сохранения текущего состояния каждой нотификации.

Вы всегда можете переделать запуск фоновых процессов в supervisor, если не хотите замарачиваться с docker контейнерами.


## Настройка систем очередей

 - [Настройка email очереди](docs/EMAIL.md)

 - [Настройка telegram очереди](docs/TELEGRAM.md)

 - [Настройка vkontakte очереди](docs/VK.md)

 - [Настройка SMS очереди](docs/SMS.md) - заглушка. Должна быть рабочей, ибо логика отправки бралась из окументации

 - [Настройка discord очереди](docs/DISCORD.md)

 - [Настройка slack очереди](docs/SLACK.md)


## Список энтрипоинтов: 

```markdown
http://localhost:85 - web заглушка
http://localhost:85/api/ - приветственный энтрипоинт
http://localhost:85/api/v1/send - POST запрос, создает задание на нотификацию
http://localhost:85/api/v1/check-status/{id} - GET запрос, проверка статуса отправки нотификации сервисом. {id} - id переданный в методе /api/v1/send
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

# работа в цикле. прослушивает очередь discord
php bin/console rabbitmq:consumer discord -vv

# работа в цикле. прослушивает очередь slack
php bin/console rabbitmq:consumer slack -vv

# очередь прослушивания исторических данных
php bin/console rabbitmq:consumer history -vv
```


## Для первого запуска тестов:

### Для тестов нужна тестовая БД **notificator_test**

Выполните команду:

```shell
# применяет миграции в базу данных для тестов
php bin/console doctrine:migrations:migrate --env test
```

Если будут ошибки, выполните команды ниже - создаст тестовую БД

```shell
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
```


# Напоминания для CI/CD:

Для деплоя на продуктив и тестовый стенд перед каждым стартом эксплуатации надо выполнять эту команду для 
генерации тем очередей RabbitMQ

```shell
php bin/console rabbitmq:setup-fabric
```

## Команда разработки проекта:

|                            | Тимлид проекта - Евгений ()        |                             |
|----------------------------|------------------------------------|-----------------------------|
| Виктор Лаптев (@fiCeVitka) | Павел Гапоненко (@pavel_gaponenko) | Сергей Галочкин (@decole12) |
