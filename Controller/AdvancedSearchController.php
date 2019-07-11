<?php

namespace Kanboard\Plugin\AdvancedFulltextSearch\Controller;


use Kanboard\Controller\BaseController;

/**
 * ASF Controller
 *
 * @package  controller
 */

class AdvancedSearchController extends BaseController
{
    /**
     * Display the ASF page
     *
     * @access public
     */
    public function index()
    {
        $this->response->html($this->helper->layout->config('AdvancedFulltextSearch:config/advanced-search-filter', array(
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
        $redirect = $this->request->getStringParam('redirect','index');
        switch ($redirect) {
            case 'index':
                $values += array(
                    'comment_search' => 0,
                    'title_search' => 0,
                    'description_search' => 0,
                    'subtask_search' => 0,
                    );
                break;
        }

        if ($this->configModel->save($values)) {
            $this->languageModel->loadCurrentLanguage();
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }
        $values = $this->configModel->getAll();
        $this->response->redirect($this->helper->url->to('AdvancedSearchController', $redirect, array('plugin' => 'AdvancedFulltextSearch',
            'values' => $values)));
    }

    /**
     * Display the ASF settings page
     *
     * @access public
     */
    public function asf()
    {
        $values = $this->configModel->getAll();
        $this->response->html($this->helper->layout->config('AdvancedFulltextSearch:config/advanced-search-filter', array(
            'title' => t('Settings').' &gt; '.t('Advanced Search Filter settings'),
        )));
    }
}
