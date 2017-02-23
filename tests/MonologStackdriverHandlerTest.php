<?php

use \Mockery;

use MonologStackdriverHandler\MonologStackdriverHandler;

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
}
