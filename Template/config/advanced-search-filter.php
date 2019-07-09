<fieldset>
    <?php
    echo $this->form->radio('adv_search_filter', t('Enable "Advanced Search Filter"'), 1,
        isset($values['adv_search_filter']) && $values['adv_search_filter'] == 1);
    echo $this->form->radio('adv_search_filter', t('Disable "Advanced Search Filter"'), 0,
        isset($values['adv_search_filter']) && $values['adv_search_filter'] == 0)
    ?>
</fieldset>