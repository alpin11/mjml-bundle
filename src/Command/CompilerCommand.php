<?php

declare(strict_types=1);

namespace Assoconnect\MJMLBundle\Command;

use Assoconnect\MJMLBundle\Compiler\Compiler;
use Assoconnect\MJMLBundle\Finder\TemplateFinder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompilerCommand extends Command
{
    protected static $defaultName = 'mjml:compiler';

    protected $compiler;

    protected $templateFinder;

    protected $projectDir;

    public function __construct(
        Compiler $compiler,
        TemplateFinder $templateFinder,
        string $projectDir
    )
    {
        parent::__construct();

        $this->compiler = $compiler;
        $this->templateFinder = $templateFinder;
        $this->projectDir = $projectDir;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Compiles a MJML template with custom tag to HTML')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This commands compiles a MJML template with custom tag to HTML. Without argument, all found templates will be compiled. Use the --template option to pick the template to compile.')
            // arguments
            ->addArgument('template', InputArgument::OPTIONAL, 'Template to compiler')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $template = $input->getArgument('template');

        if(!$template) {
            $output->writeln('No template picked, searching for templates ...');
        }

        $templates = $this->templateFinder->find($template);

        foreach($templates as $template) {
            $output->writeln('Compiling: ' . $template->getFilename());

            $this->compiler->compile($template);
        }
    }
}