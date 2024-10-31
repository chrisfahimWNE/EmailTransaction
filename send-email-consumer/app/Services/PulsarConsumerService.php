<?php

namespace App\Services;

use Pulsar\Consumer;
use Pulsar\ConsumerOptions;
use Pulsar\SubscriptionType;

use Illuminate\Support\Facades\Http;

class PulsarConsumerService
{
    protected $consumer;

    public function __construct()
    {
        $config = new ConsumerOptions();
        $config->setTopics([
            env('PULSAR_SEND_CONFIRM_EMAIL_TOPIC'),
            env('PULSAR_RECIEVE_EMAIL_TOPIC')
        ]);
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

                if($message->getTopic() === env('PULSAR_SEND_CONFIRM_EMAIL_TOPIC'))
                {
                    $payload = json_decode($message->getPayload(), true);
                    $response = Http::post('http://localhost:8000/api/send-confirm-email', $payload);

                    if ($response->successful()) {
                        echo 'successfully triggered email' . PHP_EOL;
                    } else {
                        echo 'failed to trigger email' . PHP_EOL;
                    }
                }
            }

            // Sleep for a short duration to avoid busy-waiting
            usleep(100000); // 100ms
        }
    }
}
