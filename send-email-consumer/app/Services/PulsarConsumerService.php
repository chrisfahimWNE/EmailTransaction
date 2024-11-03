<?php

namespace App\Services;

use Pulsar\Consumer;
use Pulsar\ConsumerOptions;
use Pulsar\SubscriptionType;

use Illuminate\Support\Facades\Http;

class PulsarConsumerService
{
    protected $consumer;

    const TOPIC_SUBS = [
        "send-email" => "persistent://public/default/send-email",
        "build-email" => "persistent://public/default/build-email",
    ];

    public function __construct()
    {
        $config = new ConsumerOptions();
        $config->setTopics(self::TOPIC_SUBS);
        $config->setSubscription(env('PULSAR_SUBSCRIPTION'));
        $config->setSubscriptionType(SubscriptionType::Shared);
        

        $this->consumer = new Consumer(env('PULSAR_SERVICE_URL'), $config);
        $this->consumer->connect();
    }

    public function consume()
    {
        
        while (true) {
            $message = $this->consumer->receive();

            if ($message) {
                // Process the message
                echo "Received message: " . $message->getTopic() . " | " . $message->getPayload() . PHP_EOL;

                // Acknowledge the message
                $this->consumer->ack($message);

                if($message->getTopic() === self::TOPIC_SUBS["send-email"])
                {
                    $payload = json_decode($message->getPayload(), true);
                    $response = Http::post('http://localhost:8001/api/send-email', $payload);

                    if ($response->successful()) {
                        echo 'successfully triggered email' . $response->body() . PHP_EOL;
                    } else {
                        echo 'failed to trigger email: ' . $response->body() . PHP_EOL;
                    }
                }
                elseif($message->getTopic() === self::TOPIC_SUBS["build-email"])
                {
                    $payload = json_decode($message->getPayload(), true);
                    $response = Http::post('http://localhost:8000/api/build-email', $payload);

                    if ($response->successful()) {
                        echo 'successfully triggered email' . $response->body() . PHP_EOL;
                    } else {
                        echo 'failed to trigger email: ' . $response->body() . PHP_EOL;
                    }
                }
            }

            // Sleep for a short duration to avoid busy-waiting
            usleep(100000); // 100ms
        }
    }
}
