<?php

namespace Kanboard\Plugin\KanboardSearchPlugin;

use Kanboard\Core\Filter\LexerBuilder;
use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Plugin\KanboardSearchPlugin\Filter\AdvancedSearchFilter;

class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach("template:config:sidebar",
            "KanboardSearchPlugin:config/sidebar");

        $this->route->addRoute('settings/advancedsearch', 'AdvancedSearchController', 'index',
            'KanboardSearchPlugin');

        $this->container->extend('taskLexer', function ($taskLexer, $c) {
            /**
             * @var LexerBuilder $taskLexer
             */
            $taskLexer->withFilter(AdvancedSearchFilter::getInstance()
                ->setDatabase($c['db'])
                ->setConfigModel($this->configModel)
                ->setFileModel($this->taskFileModel), true);

            return $taskLexer;
        });
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'KanboardSearchPlugin';
    }

    public function getPluginDescription()
    {
        return t('This plugin is used for advanced fulltext search within all Projects');
    }

    public function getPluginAuthor()
    {
        return 'ipunkt Business Solutions';
    }

    public function getPluginVersion()
    {
        return '1.1.0';
    }

    public function getPluginHomepage()
    {
        return 'https://www.ipunkt.biz/unternehmen/opensource';
    }
}