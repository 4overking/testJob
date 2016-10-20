<?php

namespace AppBundle\Composer;

use Composer\Script\Event;
use RuntimeException;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as DistributionScriptHandler;

class ScriptHandler extends DistributionScriptHandler
{
    /**
     * @param Event $event
     */
    public static function deploy(Event $event)
    {
        static::executeCommand($event, 'doctrine:database:create');
        static::executeCommand($event, 'doctrine:migrations:migrate --no-interaction');
        static::executeCommand($event, 'app/console cache:clear --env=prod');
    }

    /**
     * @param Event $event
     */
    public static function updateExternalConfigs(Event $event)
    {
        static::executeCommand($event, 'app:update-external-configs', null, 600); // 10 min timeout
    }

    /**
     * @param Event  $event
     * @param string $cmd
     * @param string $appDir
     * @param int    $timeout
     *
     * @throws RuntimeException
     */
    protected static function executeCommand(Event $event, $cmd, $appDir = null, $timeout = null)
    {
        $options = static::getOptions($event);

        if (null === $appDir) {
            $appDir = $options['symfony-app-dir'];
        }
        if (!is_dir($appDir)) {
            throw new RuntimeException(
                'The symfony-app-dir ('.$appDir.') specified in composer.json was not found in '.getcwd().'.'
            );
        }

        if (null === $timeout) {
            $timeout = $options['process-timeout'];
        }

        parent::executeCommand($event, $appDir, $cmd, $timeout);
    }
}
