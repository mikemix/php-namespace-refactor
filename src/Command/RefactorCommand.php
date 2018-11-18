<?php

declare(strict_types=1);

namespace mikemix\NamespaceRewrite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Finder\Finder;

class RefactorCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('namespace:refactor')
            ->setDescription('Rewrite namespaces')
            ->addArgument('namespace', InputArgument::REQUIRED, 'Target namespace')
            ->addOption('dry-run', 'd', InputOption::VALUE_OPTIONAL, 'Run without rewrite', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceDirectory = '/refactor/';
        if (!is_readable($sourceDirectory)) {
            $output->writeln(sprintf('<error>/refactor directory not mounted</error>', $sourceDirectory));

            return -1;
        }

        $namespace = $input->getArgument('namespace');

        /** @var \SplFileInfo[] $finder */
        $finder = Finder::create()->files()->in($sourceDirectory)->name('*.php');

        $table = new Table($output);
        $table->setHeaders(['Current namespace', 'New namespace']);

        foreach ($finder as $file) {
            $path = $file->getPathname();
            if (!preg_match('/namespace (.*?);/', file_get_contents($path), $matches)) {
                continue;
            }

            $currentNamespace = $matches[1];
            $validNamespace = str_replace($sourceDirectory, '', dirname($path));
            $validNamespace = $namespace . '\\' . str_replace('/', '\\', $validNamespace);

            if ($currentNamespace === $validNamespace) {
                continue;
            }

            $table->addRow([$currentNamespace, $validNamespace]);

            if (null !== $input->getOption('dry-run')) {
                @file_put_contents(
                    $path,
                    str_replace(
                        "namespace $currentNamespace;",
                        "namespace $validNamespace;",
                        file_get_contents($path)
                    )
                );
            }
        }

        $table->render();

        return 0;
    }
}
