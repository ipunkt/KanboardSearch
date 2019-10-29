<?php

namespace Kanboard\Plugin\KanboardSearchPlugin\Controller;


use Kanboard\Controller\BaseController;


class AdvancedSearchController extends BaseController
{
    /**
     * Display the ASF settings page
     *
     * @access public
     */
    public function index()
    {
        $this->response->html($this->helper->layout->config('KanboardSearchPlugin:config/advanced-search-filter', array(
            'db_size' => $this->configModel->getDatabaseSize(),
            'db_version' => $this->db->getDriver()->getDatabaseVersion(),
            'user_agent' => $this->request->getServerVariable('HTTP_USER_AGENT'),
            'title' => t('Settings').' &gt; '.t('Advanced Search Filter'),
        )));
    }

    /**
     * Save settings
     *
     */
    public function save()
    {
        $values =  $this->request->getValues();
        $redirect = $this->request->getStringParam('redirect', 'index');
        switch ($redirect) {
            case 'index':
                $values += array(
                    'comment_search' => 0,
                    'title_search' => 0,
                    'description_search' => 0,
                    'subtask_search' => 0,
                    'attachment_search' => 0,
                    'id_search' => 0
                    );
                break;
        }

        if ($this->configModel->save($values)) {
            $this->languageModel->loadCurrentLanguage();
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }
        $this->response->redirect($this->helper->url->to('AdvancedSearchController', 'index', array('plugin' => 'KanboardSearchPlugin')));
    }
}
