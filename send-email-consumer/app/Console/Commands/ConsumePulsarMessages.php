<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PulsarConsumerService;

class ConsumePulsarMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pulsar:consume';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumes messages from apache pulsar';

    protected $pulsarConsumer;

    public function __construct(PulsarConsumerService $pulsarConsumer)
    {
        parent::__construct();
        $this->pulsarConsumer = $pulsarConsumer;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->pulsarConsumer->consume();
    }
}
