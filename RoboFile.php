<?php

use League\Container\ContainerInterface;
use Sweetchuck\LintReport\Reporter\BaseReporter;
use Sweetchuck\LintReport\Reporter\CheckstyleReporter;
use Sweetchuck\LintReport\Reporter\VerboseReporter;
use Sweetchuck\Robo\Git\GitTaskLoader;
use Sweetchuck\Robo\Phpcs\PhpcsTaskLoader;
use Robo\Collection\CollectionBuilder;
use Robo\Tasks;
use Robo\Task\Base\loadTasks as BaseTaskLoader;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\PathUtil\Path;

class RoboFile extends Tasks {
    use GitTaskLoader;
    use PhpcsTaskLoader;
    use BaseTaskLoader;

    protected $composerInfo = [];

    protected $packageVendor = '';

    protected $packageName = '';

    protected $binDir = 'vendor/bin';

    protected $envNamePrefix = '';

    protected $context = '';

    protected $envNames = [
        'environment_type' => 'dev',
        'environment_name' => 'local',
    ];

    public function __construct()
    {
        putenv('COMPOSER_DISABLE_XDEBUG_WARN=1');
        $this
            ->initComposerInfo()
            ->initEnvNamePrefix();
    }

    protected function initComposerInfo()
    {
        if ($this->composerInfo || !is_readable('composer.json')) {
            return $this;
        }

        $this->composerInfo = json_decode(file_get_contents('composer.json'), true);
        list($this->packageVendor, $this->packageName) = explode('/', $this->composerInfo['name']);

        if (!empty($this->composerInfo['config']['bin-dir'])) {
            $this->binDir = $this->composerInfo['config']['bin-dir'];
        }

        return $this;
    }

    protected function initEnvNamePrefix()
    {
        $this->envNamePrefix = strtoupper(str_replace('-','_', $this->packageName));

        return $this;
    }

    /**
    * {@inheritdoc}
    */
    public function setContainer(ContainerInterface $container)
    {
        BaseReporter::lintReportConfigureContainer($container);
        return parent::setContainer($container);
    }

    /**
     * Git "pre-commit" hook callback.
     */
    public function githookPreCommit(): CollectionBuilder
    {
        $this->context = 'git-hook';
        return $this
            ->collectionBuilder()
            ->addTaskList([
                'lint.composer.lock' => $this->taskComposerValidate(),
                'lint.phpcs' => $this->getTaskPhpcs(),
                'phpunit.unit' => $this->getTaskPhpUnit(),
            ]);
    }

    /**
     * @return \Sweetchuck\Robo\Phpcs\Task\PhpcsLintFiles|\Robo\Collection\CollectionBuilder
     */
    protected function getTaskPhpcsLint()
    {
        $envType = $this->getEnvironmentType();
        $envName = $this->getEnvironmentName();
        $files = [
            'src/',
            'src-dev/',
            'RoboFile.php',
        ];
        $options = [
            'failOn' => 'warning',
            'standards' => ['PSR2'],
            'lintReporters' => [
                'lintVerboseReporter' => (new VerboseReporter())->showSource(true),
            ],
        ];

        return $this
            ->collectionBuilder()
            ->addTaskList([
                'git.readStagedFiles' => $this
                    ->taskGitReadStagedFiles()
                    ->setCommandOnly(true)
                    ->setPaths($files),
                'lint.phpcs' => $this
                    ->taskPhpcsLintInput($options)
                    ->setIgnore([
                        '*.scss',
                        '*.txt',
                        '*.yml',
                    ]),
            ]);
    }

    protected function getEnvironmentType(): string
    {
        return $this->getEnv('environment_type');
    }

    protected function getEnv(string $envName): string
    {
        $default = $this->envNames[$envName] ?? null;
        return getenv($this->getEnvName($envName)) ?: $default;
    }

    protected function getEnvName(string $name): string
    {
        return "{$this->envNamePrefix}_" . strtoupper($name);
    }

    protected function getEnvironmentName(): string
    {
        return $this->getEnv('environment_name');
    }

    protected function getTaskPhpcs()
    {
        /** @var \Robo\Task\Base\ExecStack $task */
        $task = $this->taskExecStack();
        $cmdPattern = Path::join($this->binDir, 'phpcs');
        $cmdArgs = [];
        $cmd = vsprintf($cmdPattern, $cmdArgs);
        $task->exec($cmd);
        return $task;
    }

    /**
     * @return \Robo\Task\Base\ExecStack|\Robo\Collection\CollectionBuilder
     */
    protected function getTaskPhpUnit()
    {
        /** @var \Robo\Task\Base\ExecStack $task */
        $task = $this->taskExecStack();
        $cmdPattern = Path::join($this->binDir, 'phpunit');
        $cmdArgs = [];
        if ($this->context === 'git-hook') {
            $cmdPattern .= ' --testsuite %s';
            $cmdArgs[] = 'Unit';
        }
        $cmd = vsprintf($cmdPattern, $cmdArgs);
        $task->exec($cmd);
        return $task;
    }

    /**
     * Run code style checkers.
     */
    public function lint(): CollectionBuilder
    {
        return $this
            ->collectionBuilder()
            ->addTaskList([
                'lint.composer.lock' => $this->taskComposerValidate(),
                'lint.phpcs' => $this->getTaskPhpcs(),
            ]);
    }
    protected function errorOutput(): ?OutputInterface
    {
        $output = $this->output();
        return ($output instanceof ConsoleOutputInterface) ? $output->getErrorOutput() : $output;
    }
}
