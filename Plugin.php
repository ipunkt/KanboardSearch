<?php

namespace Kanboard\Plugin\KanboardSuche;


use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
    public function initialize()
    {

    }

    public function getPluginName()
    {
        return 'Kanboard Suche';
    }

    public function getPluginDescription()
    {
        return t('This plugin is used for search in Kanboard');
    }

    public function getPluginAuthor()
    {
        return 'ipunkt Business Solutions';
    }

    public function getPluginVersion()
    {
        return '1.0';
    }

    public function getPluginHomepage()
    {
        return '';
    }

}