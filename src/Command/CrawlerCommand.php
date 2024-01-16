<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CrawlerCommand extends Command
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        parent::__construct();

        $this->httpClient = $httpClient;
    }

    protected function configure(): void
    {
        $this->setName('crawler')
        ->setDescription('Crawl a URL.')
        ->addArgument('url', InputArgument::REQUIRED, 'The URL to crawl')
        ->addArgument('string', InputArgument::REQUIRED, 'The string to search');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = $input->getArgument('url');
        $string = $input->getArgument('string');

        $response = $this->httpClient->request('GET', $url);
        $content = $response->getContent();

        if (str_contains($content, $string)) {
             $output->writeln('Success');
            return Command::SUCCESS;
        }

        $output->writeln('Failure');
        return Command::FAILURE;
    }
}
