## Настройка очереди VK (Вконтакте) оповещений

Оповещение производится в чат сообщевта, а не в личную переписку пользователя.
(Было сделано такое решение, дабы долго не разбираться со странной документацией и подводными камнями)

> Мы действительно пытались. Но нас поджимало время. 

Используется официальное VK SDK for php - https://vk.com/dev/PHP_SDK

1. Берем создаем сообщество в Вконтакте.
2. Заходим во вкладку "Упарвление"
3. Заходим в подпункт "Работа с API"
4. Создаем ключ доступа, копируем текст ключа в .env.local - **VK_ACCES_KEY** пример ключа `vk1.a.DDDasda0-CCCccc-czN ... xB3`
5. **VK_GROUP_ID** - в url группы берем номер, пример url: `https://vk.com/club214634125`, значит в .env.local `VK_GROUP_ID=214634125`
6. **VK_PEER_ID** - нашел так: зашел в чат сообщества, написа в чат от своего имени. ищем запрос POST https://vk.com/al_im.php?act=a_send и в **to** b будет **VK_PPER_ID**

```shell
POST https://vk.com/al_im.php?act=a_send 

Form Data

act: a_send
al: 1
entrypoint: 
gid: 214634125
guid: 165834992864361
hash: 1658349922_4181a8905c8327cf6f
im_v: 3
media: 
module: im
msg: 123
random_id: 116680092
to: 2000000001   <------ VK_PEER_ID
```

в итоге у вас должен быть такой конфиг в .env.local

```shell
VK_ACCES_KEY="vk1.a.DDDasda0-CCCccc-czN ... xB3"
VK_GROUP_ID=214634125
VK_PEER_ID=2000000001
```

## Отправка нотификации

POST http://localhost:85/api/v1/send

```json
{
    "type": "vk",
    "message": "test notification message"
}
```