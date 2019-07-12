<?php

namespace Kanboard\Plugin\AdvancedFulltextSearch\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseFilter;
use Kanboard\Model\CommentModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskModel;
use Kanboard\Model\ConfigModel;
use PicoDb\Database;


class AdvancedSearchFilter extends BaseFilter implements FilterInterface
{

    /**
     * Database object
     *
     * @access private
     * @var Database
     */
    private $db;

    /**
     * @var ConfigModel
     */
    private $config;

    /**
     * Set database object
     *
     * @access public
     * @param  Database $db
     * @return AdvancedSearchFilter
     */
    public function setDatabase(Database $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Set configModel object
     *
     * @access public
     * @param ConfigModel $config
     * @return AdvancedSearchFilter
     */
    public function setConfigModel(ConfigModel $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('title', 'comment', 'description', 'desc');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return string
     */
    public function apply()
    {
        $commentTaskIds = $this->getTaskIdsWithGivenComment();
        $titlesTaskIds = $this->getTaskIdsWithGivenTitles();
        $descriptionTaskIds = $this->getTaskIdsWithGivenDescription();
        $subtaskTitlesIds = $this->getTaskIdsWithGivenSubtaskTitles();

        $task_ids = array_merge($commentTaskIds, $titlesTaskIds, $descriptionTaskIds, $subtaskTitlesIds);

        if (empty($task_ids)) {
            $task_ids = array(-1);
        }
        $this->query->in(TaskModel::TABLE . '.id', $task_ids);

        return $this;
    }

    /**
     * Get task ids having this comment
     *
     * @access public
     * @return array
     */
    protected function getTaskIdsWithGivenComment()
    {
        if($this->config->get('comment_search') == 1) {
            return $this->db
                ->table(CommentModel::TABLE)
                ->ilike(CommentModel::TABLE . '.comment', '%' . $this->value . '%')
                ->findAllByColumn(CommentModel::TABLE . '.task_id');
        }
        return array();
    }


    /**
     * Get task ids having this description
     *
     * @access public
     * @return array
     */
    protected function getTaskIdsWithGivenDescription()
    {
        if($this->config->get('description_search') == 1) {
            return $this->db
                ->table(TaskModel::TABLE)
                ->ilike(TaskModel::TABLE . '.description', '%' . $this->value . '%')
                ->findAllByColumn(TaskModel::TABLE . '.id');
        }
        return array();
    }


    /**
     * Get task ids having this title
     *
     * @access public
     * @return array
     */
    private function getTaskIdsWithGivenTitles()
    {
        if($this->config->get('title_search') == 1) {
            return $this->db
                ->table(TaskModel::TABLE)
                ->ilike(TaskModel::TABLE . '.title', '%' . $this->value . '%')
                ->findAllByColumn(TaskModel::TABLE . '.id');
        }
        return array();
    }


    /**
     * Get task ids having this Subtask title
     *
     * @access public
     * @return array
     */
    private function getTaskIdsWithGivenSubtaskTitles()
    {
        if($this->config->get('subtask_search') == 1) {
            return $this->db
                ->table(SubtaskModel::TABLE)
                ->ilike(SubtaskModel::TABLE . '.title', '%' . $this->value . '%')
                ->findAllByColumn(SubtaskModel::TABLE . '.task_id');
        }
        return array();
    }
}