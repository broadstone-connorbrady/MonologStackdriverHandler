<?php

namespace MonologStackdriverHandler;

use Google\Cloud\Logging\LoggingClient;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class MonologStackdriverHandler extends AbstractProcessingHandler
{
    /*
     * @var array
     */
    protected $options;

    public function __construct($googleProjectId, $logName, $options, $logging=null)
    {
        if (is_null($logging)) {
            $logging = new LoggingClient([
                'projectId' => $googleProjectId,
            ]);
        }
        $this->logger = $logging->logger($logName);

        // set logger options.
        // see http://googlecloudplatform.github.io/google-cloud-php/#/
        $this->options = array_merge([
            'resource' => [
                'type' => 'global',
            ],
            'labels' => [
                'project_id' => $googleProjectId,
            ],
            'timestamp' => date('Y-m-dTH:i:sZ'),
        ], $options);
    }

    public function write(array $record)
    {
        if (!isset($record['formatted']) || 'string' !== gettype($record['formatted']))
        {
            throw new \InvalidArgumentException('StackdriverHandler accepts only formatted records as a string');
        }
        $this->logger->write($record['formatted'], $this->options);
    }
}
