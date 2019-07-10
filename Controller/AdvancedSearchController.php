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
     * Display the about page
     *
     * @access public
     */
    public function index()
    {
        $this->response->html($this->helper->layout->config('config/advanced-search-filter', array(
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
        $redirect = $this->request->getStringParam('redirect', 'advanced-search-filter');

        switch ($redirect) {
            case 'advanced-search-filter':
                $values += array('comment_search' => 0);
                $values += array('title_search' => 0);
                $values += array('description_search' => 0);
                $values += array('subtask_search' => 0);
                break;
        }

        if ($this->configModel->save($values)) {
            $this->languageModel->loadCurrentLanguage();
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }

        $this->response->redirect($this->helper->url->to('AdvancedSearchController', $redirect));
    }
}
