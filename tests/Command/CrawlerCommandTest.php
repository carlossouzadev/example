<?php

namespace App\Tests\Command;

use App\Command\CrawlerCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CrawlerCommandTest extends KernelTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();
    }

    public function testExecute(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturnCallback(function () {
            $mockedResponseContent = 'En';
            $response = $this->createMock(ResponseInterface::class);
            $response->method('getContent')->willReturn($mockedResponseContent);

            return $response;
        });

        $application = new Application(self::$kernel);
        $application->add(new CrawlerCommand($httpClient));

        $command = $application->find('crawler');

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'url' => 'https://www.mobilexpense.com/',
            'string' => 'En',
        ]);

        $status = $commandTester->getStatusCode();
        $this->assertEquals('Success', trim($commandTester->getDisplay()));
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
