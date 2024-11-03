<?php

namespace App\Services;

use Pulsar\Compression\Compression;
use Pulsar\Producer;
use Pulsar\ProducerOptions;

class PulsarService
{


    public function __construct()
    {
        //nothing to do here
    }
        
    
    private function getProducer(string $topic)
    {
        $options = new ProducerOptions();
        $options->setTopic($topic);
        $options->setCompression(Compression::ZLIB);
        $producer = new Producer(env('PULSAR_SERVICE_URL'), $options);
        $producer->connect();
        return $producer;
        
    }

    public function produceMessage(string $topic, array|object $message)
    {
        $producer = $this->getProducer($topic);

        // Convert the message to JSON
        $jsonMessage = json_encode($message);

        // Send the JSON message
        $producer->send($jsonMessage);

        $producer->close();
        return true;
    }

  
}
