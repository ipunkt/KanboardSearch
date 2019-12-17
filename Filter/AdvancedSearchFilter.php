<?php

namespace Kanboard\Plugin\KanboardSearchPlugin\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseFilter;
use Kanboard\Model\CommentModel;
use Kanboard\Model\TaskFileModel;
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
     * @var TaskFileModel
     */
    private $file;

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
     * Set TaskFileModel object
     *
     * @access public
     * @param TaskFileModel $file
     * @return AdvancedSearchFilter
     */
    public function setFileModel(TaskFileModel $file)
    {
        $this->file = $file;
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
        return array('title', 'comment', 'description', 'desc', 'taskId');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return string
     */
    public function apply()
    {
        if( $this->isIdOnlySearch() ) {

            $taskId = $this->valueToTaskOnlyId();
            $this->query->eq(TaskModel::TABLE . '.id', $taskId);

        return $this;
    }

        $commentTaskIds = $this->getTaskIdsWithGivenComment();
        $titlesTaskIds = $this->getTaskIdsWithGivenTitles();
        $descriptionTaskIds = $this->getTaskIdsWithGivenDescription();
        $subtaskTitlesIds = $this->getTaskIdsWithGivenSubtaskTitles();
        $attachmentIds = $this->getTaskIdsWithGivenAttachmentName();
        $taskIds = $this->getTaskIdsWithGivenId();

        $task_ids = array_merge($commentTaskIds, $titlesTaskIds, $descriptionTaskIds, $subtaskTitlesIds, $attachmentIds, $taskIds);

        if (empty($task_ids)) {
            $task_ids = array(-1);
        }
        $this->query->in(TaskModel::TABLE . '.id', $task_ids);

        return $this;
    }

    protected function isIdOnlySearch() {
        $trimmed_value = trim($this->value);
        if( empty($trimmed_value) )
            return false;

        if( $trimmed_value[0] !== '#' )
            return false;

        return true;
    }

    protected function valueToTaskOnlyId() {
        $trimmed_value = trim($this->value);

        $value_without_hash = substr($trimmed_value, 1);

        return (int)$value_without_hash;
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


    /**
     * Get task ids having this Attachment Name
     *
     * @access public
     * @return array
     */
    private function getTaskIdsWithGivenAttachmentName()
    {
        if($this->config->get('attachment_search') == 1) {
            return $this->db
                ->table(TaskFileModel::TABLE)
                ->ilike(TaskFileModel::TABLE . '.name', '%' . $this->value . '%')
                ->findAllByColumn(TaskFileModel::TABLE . '.task_id');
        }
        return array();
    }
    /**
     * Get task ids having this id}
     *
     * @access public
     * @return array
     */

    private function getTaskIdsWithGivenId()
	{
		if(ctype_digit($this->value) and $this->config->get('id_search') == 1) {
			return $this->db
				->table(TaskModel::TABLE)
				->eq(TaskModel::TABLE . '.id', $this->value)
				->findAllByColumn(TaskModel::TABLE . '.id');
		}
		return array();
	}
}
