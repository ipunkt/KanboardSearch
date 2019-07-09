<?php

namespace Kanboard\Plugin\AdvancedFulltextSearch;

use Kanboard\Core\Filter\LexerBuilder;
use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\AdvancedFulltextSearch\Filter\AdvancedSearchFilter;

class Plugin extends Base
{
    public function initialize()
    {

        $this->template->hook->attach("template:config:application",
            "AdvancedFulltextSearch:config/advanced-search-filter");
        if ($this->configModel->get('adv_search_filter', 1) == 1) {
            $this->container->extend('taskLexer', function ($taskLexer, $c) {
                /**
                 * @var LexerBuilder $taskLexer
                 */
                $taskLexer->withFilter(AdvancedSearchFilter::getInstance()
                    ->setDatabase($c['db']), true);

                return $taskLexer;
            });
        };
    }

    public function getPluginName()
    {
        return 'AdvancedFulltextSearch';
    }

    public function getPluginDescription()
    {
        return t('This plugin is used for advanced fulltext search within given Project in Kanboard');
    }

    public function getPluginAuthor()
    {
        return 'ipunkt Business Solutions';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return '';
    }
}