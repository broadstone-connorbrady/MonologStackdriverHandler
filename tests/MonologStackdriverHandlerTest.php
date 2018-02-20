<?php

use Google\Cloud\Logging\Logger;
use Google\Cloud\Logging\LoggingClient;
use MonologStackdriverHandler\MonologStackdriverHandler;
use Prophecy\Argument;

class MonologStackdriverHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $googleProjectId;
    protected $formattedText;
    protected $logName;
    protected $record;
    protected $options;

    protected function setUp()
    {
        $this->googleProjectId = 'project_id';
        $this->formattedText   = 'formatted log text';
        $this->logName         = 'name-of-the-log';
        $this->type            = 'global';
        $this->record = [
            'formatted' => $this->formattedText,
        ];
        $this->options = [
            'resource' => [
                'type' => 'global',
            ],
            'labels' => [
                'project_id' => $this->googleProjectId,
            ],
            'timestamp' => '2017-01-01T00:00:00Z',
        ];
    }

    public function testWrite()
    {
        $loggerMock = Mockery::mock('logger');
        $loggerMock
            ->shouldReceive('write')
            ->with($this->formattedText, $this->options);

        $loggingMock = Mockery::mock('logging');
        $loggingMock
            ->shouldReceive('logger')
            ->with($this->logName)
            ->andReturn($loggerMock);

        $handler = new MonologStackdriverHandler($this->googleProjectId, $this->logName, $this->options, $loggingMock);
        $handler->write($this->record);
    }

    public function testWriteWithGeneratedTimestamp()
    {
        $logger = $this->prophesize(Logger::class);
        $optionsArgument = function ($options) {
            self::assertArrayHasKey('timestamp', $options);
            self::assertRegExp('/\d{2}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z/', $options['timestamp']);

            return true;
        };
        $logger->write($this->formattedText, Argument::that($optionsArgument))->shouldBeCalled();

        $logging = $this->prophesize(LoggingClient::class);
        $logging->logger($this->logName)->shouldBeCalled()->willReturn($logger->reveal());

        $handler = new MonologStackdriverHandler(
            $this->googleProjectId,
            $this->logName,
            $this->options,
            $logging->reveal()
        );
        $handler->write($this->record);
    }
}
