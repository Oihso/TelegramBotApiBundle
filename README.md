TelegramBotApiBundle
===================
A symfony wrapper bundle for  [Telegram Bot API](https://core.telegram.org/bots/api).

## What's the difference with the original one?
- Updated to Symfony 6.4/~~7.0~~ (Temporarily removed Symfony 7 support until the authors of irazasyed/telegram-bot-sdk and illuminate/support will bump their package requirements)
- Fixed some deprecations

## Install

Via Composer

``` bash
composer require oihso/telegram-bot-api-bundle
```

### If moving from original bundle
1. Remove old package by running `composer remove borsaco/telegram-bot-api-bundle`
2. Change use statements in your code from `Borsaco\...` to `Oihso\...`
3. Install new package by running `composer require oihso/telegram-bot-api-bundle`

New version of package is fully compataible with the old one. No need to change config/code except for use statements


## Configure the bundle

This bundle was designed to just work out of the box. The only thing you have to configure in order to get this bundle up and running is your bot [token](https://core.telegram.org/bots#botfather).

```yaml
# config/packages/telegram.yaml

telegram_bot_api:
    # Proxy (optional) :
    #proxy: 'socks5h://127.0.0.1:5090' # this is example you have to change this
    #async_requests: false

    # Development section:
    development:
        # implement in next version
        # Telegram user_id of developers accounts
        developers_id: [1234567, 87654321]
        # If this mode is enabled, the robot only responds to the developers
        maintenance:
            text: "The robot is being repaired! Please come back later."
  
    # Bots:
    bots:
        # The bot name
        first:
            # Your bot token: (required)
            token: 123456789:ABCD1234****4321CBA
            maintenance: false
        second:
            # Your bot token: (required)
            token: 123456789:ABCD1234****4321CBA
            maintenance: false
    
    # The default bot returned when you call getBot()
    default: 'second' 
```

## Usage

You can access the bot in the controller with :
```php
    use Oihso\TelegramBotApiBundle\Service\Bot;

    ...

    public function index(Bot $bot)
    {
        $firstBot = $bot->getBot('first');
        $firstBot->getMe();
    }
```

## Webhook
In order to receive updates via a Webhook, You first need to tell your webhook URL to Telegram. You can use setWebhook method to specify a url and receive incoming updates via an outgoing webhook or use this commands:.

for get information about webhook of bot:
```bash
    $ php bin/console telegram:bot:webhook:info
```

for set webhook url for the bot:
```bash
    $ php bin/console telegram:bot:webhook:set
```

for delete webhook of the bot:
```bash
    $ php bin/console telegram:bot:webhook:delete
```

Once you set the webhook using the setWebhook method, You can then use the below function to retrieve the updates that are sent to your Webhook URL. The function returns an array of Update objects.
```php
    $updateArray = $firstBot()->getWebhookUpdate();
```

## Maintenance

If want to use maintenance of bots you can check `$bot->isMaintenance('bot_name')` in your entry point controller and send response message.

## Next...

Please refer to [Telegram Bot API Official Document](https://core.telegram.org/bots/api) for getting information about available methods and other informations.

## Troubleshooting

If you did all the configurations correctly but still getting errors (Http error 500) even on getMe() method, it might be because of SSL Verification. Please make sure you have up-to-date CA root certificate bundle to be used with cURL.

You can configure you CA root certificate bundle by:

 1. Downloading up-to-date cacert.pem file from cURL website and
 2. Setting a path to it in your php.ini file, e.g. on Windows:

 `curl.cainfo=c:\php\cacert.pem`

You can test your SSL-setup online with this handy webtool on: [SSL Labs](https://www.ssllabs.com/ssltest)

## License

The BSD License. Please see [License File](LICENSE) for more information.
