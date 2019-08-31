<?php


namespace Idmkr\ImportWoocommerceUsers\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportWoocommerceUsers extends Command
{

    const NAME_ARGUMENT = "name";
    const NAME_OPTION = "option";


    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $name = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $objectManager
            ->get('Idmkr\ImportWoocommerceUsers\Manager\ImportOrderManager')
            ->process();
        //$output->writeln("Hello " . $name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("idmkr_importwoocommerceusers:importwoocommerceusers");
        $this->setDescription("import woocommerce users");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        parent::configure();
    }
}
