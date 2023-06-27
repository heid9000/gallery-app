<?php

namespace App\Command;

use App\Service\ImageService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:sync-images')]
class SyncImagesCommand extends Command
{

    /**
     * @param ImageService $imageService
     */
    public function __construct(
        protected ImageService $imageService
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('daemon', 'd', InputOption::VALUE_NONE, 'run as daemon');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('daemon')) {
            $carry = new \DateTime();
            while (true) {
                $now = new \DateTime();
                $d = $now->getTimestamp() - $carry->getTimestamp();
                if ($d > 4) {
                    $output->writeln('Beginning new sync...');
                    $this->imageService->sync();
                    $carry = $now;
                }
            }
        } else {
            $this->imageService->sync();
        }
        return 0;
    }
}
