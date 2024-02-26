<?php

namespace Oihso\TelegramBotApiBundle\DependencyInjection\Factory;

use GuzzleHttp\Client;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\HttpClients\GuzzleHttpClient;

class BotFactory
{
	/**
	 * @throws TelegramSDKException
	 */
	public function create(array $config, string $name): Api
    {
        $bot = new Api($config['bots'][$name]['token']);

        if($config['proxy']) {
            $client = new GuzzleHttpClient(new Client(['proxy' => $config['proxy']]));
            $bot->setHttpClientHandler($client);
        }

        if($config['async_requests']) {
            $bot->setAsyncRequest($config['async_requests']);
        }

        return $bot;
    }
}
