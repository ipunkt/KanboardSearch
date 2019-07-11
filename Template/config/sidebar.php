<li <?= $this->app->checkMenuSelection('AdvancedSearchController', 'asf') ?>>
    <?= $this->url->link(t('Advanced Search Filter settings'), 'AdvancedSearchController', 'asf',
        ['plugin' => 'AdvancedFulltextSearch']) ?>
</li>
