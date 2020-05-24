<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\WorldService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class WorldCommand
 * @package App\Command
 */
class WorldCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'app:world';
    private const DESCRIPTION = 'Reprezentace světa obsahující organismy s maticí m:n.';
    private const OUTPUT_FILE_NAME = 'world.xml';

    private SymfonyStyle $io;
    private KernelInterface $appKernel;
    private WorldService $worldService;

    public function __construct(KernelInterface $appKernel, WorldService $worldService)
    {
        parent::__construct();
        $this->appKernel = $appKernel;
        $this->worldService = $worldService;
    }

    protected function configure(): void
    {
        $this->setName(self::$defaultName)
            ->setDescription(self::DESCRIPTION)
            ->setHelp(self::DESCRIPTION)
            ->addArgument(
                'dimensionX',
                InputArgument::REQUIRED,
                'Rozměr X čtvercového světa'
            )->addArgument(
                'dimensionY',
                InputArgument::REQUIRED,
                'Rozměr Y čtvercového světa'
            )->addArgument(
                'species',
                InputArgument::REQUIRED,
                'Počet unikátních druhů'
            )->addArgument(
                'iterations',
                InputArgument::REQUIRED,
                'Počet iterací'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title(self::DESCRIPTION);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $dimensionX = (int) $input->getArgument('dimensionX');
        $dimensionY = (int) $input->getArgument('dimensionY');
        $species = (int) $input->getArgument('species');
        $iterations = (int) $input->getArgument('iterations');

        $world = $this->worldService->createWorld($dimensionX, $dimensionY, $species, $iterations);

        $encoders = [new XmlEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $xml = $serializer->serialize($world->getWorldArray(), 'xml', ['xml_root_node_name' => 'world']);

        $outputFile = fopen($this->appKernel->getProjectDir() . '/public/' . self::OUTPUT_FILE_NAME, "w");
        fwrite($outputFile, $xml);
        fclose($outputFile);

        return 0;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        foreach ($input->getArguments() as $option => $value) {
            if ($value === null) {
                $input->setArgument($option, $this->io->ask(sprintf('%s', ucfirst($option))));
            }
        }
    }
}