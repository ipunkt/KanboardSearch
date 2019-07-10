<div class="page-header">
    <h2><?= t('Advanced Search Filter') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('AdvancedSearchController', 'save', array('redirect' => 'advanced-search-filter')) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <fieldset>
        <?= $this->form->checkbox('comment_search', t('Enable "Search in Comment"'), 1, $values['comment_search'] == 1) ?>
    </fieldset>
    <fieldset>
        <?= $this->form->checkbox('title_search', t('Enable "Search in Title"'), 1, $values['title_search'] == 1) ?>
    </fieldset>

    <fieldset>
        <?= $this->form->checkbox('description_search', t('Enable "Search in Description"'), 1, $values['description_search'] == 1) ?>
    </fieldset>

    <fieldset>
        <?= $this->form->checkbox('subtask_search', t('Enable "Search in Subtask Title"'), 1, $values['subtask_search'] == 1) ?>
    </fieldset>


    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save') ?></button>
    </div>
</form>