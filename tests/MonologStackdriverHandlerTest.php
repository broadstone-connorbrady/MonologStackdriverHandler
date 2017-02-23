<?php

use \Mockery;

use MonologStackdriverHandler\MonologStackdriverHandler;

class MonologStackdriverHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $logName;
    protected $record;
    protected $options;

    protected function setUp()
    {
        $this->logName   = 'name-of-the-log';
        $this->type      = 'global';
        $this->record = [
            'formatted' => 'formatted log text',
        ];
        $this->options = [
            'resource' => [
                'type' => 'global',
            ],
            'labels' => [
                'project_id' => 'project_id',
            ],
            'timestamp' => '2017-01-01T00:00:00Z',
        ];
    }

    public function testWrite()
    {
        $loggerMock = Mockery::mock('logger');
        $loggerMock
            ->shouldReceive('write')
            ->with(
                Mockery::subset($this->record),
                Mockery::subset($this->options)
            );

        $loggingMock = Mockery::mock('logging');
        $loggingMock
            ->shouldReceive('logger')
            ->with($this->logName)
            ->andReturn($loggerMock);

        $handler = new MonologStackdriverHandler($loggingMock);
        $handler->write($this->record, $this->options);
    }
}
