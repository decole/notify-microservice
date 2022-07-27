## Настройка очереди SMS оповещений

Опирались на документацию https://smspilot.ru/apikey.php#sms

Практическое использование не проверялось. Добавили код из доступной документации из официального сайта.

Запуск очерели на отправку сообщений

```shell
php bin/console rabbitmq:consumer sms -vv
```

### Настройка смс провайдера

Для кастомизации, укажите **SMS_PROVIDER** свой класс провайдера и реализуйте его.

Реализован СМС провайдер smspilot (https://smspilot.ru/apikey.php#sms).

Чтобы кастомизировать провайдер, поместите свою реализациюв в `src/Infrastructure/Sender/Sms/Provider`

Ваш провайдер должен иметь суфикс Provider.

т.е. если вы создаете провайдер с именем **custom**, то должны назвать свой класс провайдера как `CustomProvider` и 
лежать он должен в папке `src/Infrastructure/Sender/Sms/Provider`

Так же в `.env.local` нужно указать название провайдера с маленькой буквы - **custom**

Пример конфига в `.env.local` 

```shell
SMS_TOKEN=ваш токен от смс сервиса
SMS_PROVIDER=custom
```

В папке `src/Infrastructure/Sender/Sms/Provider` для примера лежит `SmspilotProvider.php`

Данный провайдер использует API токен из `.env.local` 
- **SMS_TOKEN** - изменить текст токена на свой
- **SMS_PROVIDER** - провайдер **smspilot** (https://smspilot.ru/apikey.php#sms) - `src/Infrastructure/Sender/Sms/Provider/SmspilotProvider.php`

```shell
SMS_TOKEN=8888888888888888888888888
SMS_PROVIDER=smspilot
```

## Как отправить нотификацию

Далее отправить запрос на микросервис

POST `http://localhost:85/api/v1/send`

```json
{
    "type": "sms",
    "phone": 79619619612,
    "message": "test"
}
```