<?php
namespace AppBundle\Command;

use AppBundle\Service\XmlImportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AutoImportCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:auto-import')
            ->setDescription('Make import from xml')
        ;
    }

    /**
     * {@inheritdoc}
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $root = $this->getContainer()->get('kernel')->getRootDir();
        $result = $this->getXmlImportService()->load($root . '/import/products.xml');

        $output->writeln('Import done!');
        $output->writeln(sprintf('Totally handled records: %s', $result->getNewCount()));
        $output->writeln(sprintf('New records: %s', $result->getNewCount()));
        $output->writeln(sprintf('Updated records: %s', $result->getUpdatedCount()));
    }

    /**
     * @return XmlImportService
     */
    protected function getXmlImportService()
    {
        return $this->getContainer()->get('app.xml_import_service');
    }
}
