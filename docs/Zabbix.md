## Настройка интеграции с Zabbix 

1. [Импортировать Media type микросервиса](zbx_export_mediatypes.yaml) в "Administration"->"Media types"
2. Активировать импортированный **media type**, если не менять название, стандартное название созданного **media type** - `Microservice`
3. Зайти в "Configuration"->"Actions"->"Trigger actions" и добавить триггеры на оповещения, в них указать способ оповещения - созданный **media type** микросервиса  
4. Настроить у пользователей методы оповещения.

### Чтобы оповещало через микросервис 
Зайди в "Configuration"->"Actions"->"Trigger actions" и укажи в триггерах способ оповещения - созданный **media type** микросервиса. смотри **Пункт 1**. 

### Конфигурационный файл из экспорта настроек Media types
[Exported config file](zbx_export_mediatypes.yaml)


## Ручная настройка вэб-хук оповещения через наш микросервис

1. Создать Media type "WebHook to microservice (microservice.otus.uberserver.ru)"
2. type: **WEBHOOK**
3. Параметры
   1. Message - {ALERT.MESSAGE}
   2. Subject - {ALERT.SUBJECT}
   3. To - **телеграм user id** или {ALERT.SENDTO}
4. Настраиваем триггер и параметры оповещения
5. Устанавливаем нашему пользователю или группе пользователей созданный нами Media type как основную систему оповещения (алертинга)

### Код js сценария:

```js
var Microservice = {
   to: null,
   message: null,
   type: "telegram",

   sendMessage: function () {
      var params = {
                 userId: Microservice.to,
                 message: Microservice.message,
                 type: Microservice.type
              },
              data,
              response,
              request = new HttpRequest(),
              url = 'https://microservice.otus.uberserver.ru/api/v1/send';

      request.addHeader('Content-Type: application/json');
      data = JSON.stringify(params);

      // Remove replace() function if you want to see the exposed token in the log file.
      Zabbix.log(4, '[Microservice Webhook] URL: ' + url);
      Zabbix.log(4, '[Microservice Webhook] params: ' + data);
      response = request.post(url, data);
      Zabbix.log(4, '[Microservice Webhook] HTTP code: ' + request.getStatus());

      try {
         response = JSON.parse(response);
      }
      catch (error) {
         response = null;
      }

      if (request.getStatus() !== 201 || typeof response.status !== 'string') {
         if (typeof response.errorText === 'string') {
            throw response.errorText;
         }
         else {
            throw 'Unknown error. Check debug log for more information.';
         }
      }
   }
};

try {
   var params = JSON.parse(value);

   if (typeof params.To === 'undefined') {
      throw 'Incorrect value is given for parameter "To": parameter is missing';
   }

   Microservice.to = params.To;
   Microservice.message = params.Subject + '\n' + params.Message;


   Microservice.sendMessage();

   return 'OK';
}
catch (error) {
   Zabbix.log(4, '[Microservice Webhook] notification failed: ' + error);
   throw 'Sending failed: ' + error + '.';
}
```