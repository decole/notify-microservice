## Настройка очереди телеграм оповещений

1. Создать своего телеграм бота через https://telegram.me/botfather (https://sendpulse.com/ru/knowledge-base/chatbot/create-telegram-chatbot)
2. Берем созданный api token из чата **botfather**
3. Копируем в `.env.local` токен созданного бота. Пример: `TELEGRAM_BOT_TOKEN=0000000000:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA`
4. !!! Добавляем созданного бота себе и пользователю, которому нужно будет слать оповещения с данного бота. 

# Если оповещаемый пользователь не добавит бота к себе в контакты, то оповещения не будут доходить до него.

## Как отправить нотификацию

Уточнить id оповещаемого пользователя. [Бот для уточнения своего telegram-ID](https://t.me/username_to_id_bot)

Далее отправить запрос на микросервис

POST `http://localhost:85/api/v1/send`

```json
{
    "type": "telegram",
    "userId": "1234567890",
    "message": "test notification message"
}
```

где:
 - **type** - обязательно указываем тип как **telegram**
 - **userId** - id оповещаемого пользователя, по ссылке узнаем id - [Бот для уточнения своего telegram-ID](https://t.me/username_to_id_bot)
 - **message** - сообщение, которое нужно передать пользователю


https://github.com/irazasyed/telegram-bot-sdk - пакет, который используется в проекте

https://telegram-bot-sdk.readme.io/docs/getting-started - документация к используемой библиотеке