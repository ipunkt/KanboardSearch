<li <?= $this->app->checkMenuSelection('AdvancedSearchController', 'index') ?>>
    <?= $this->url->link(t('Advanced Search Filter settings'), 'AdvancedSearchController', 'index',
        ['plugin' => 'KanboardSearchPlugin']) ?>
</li>
