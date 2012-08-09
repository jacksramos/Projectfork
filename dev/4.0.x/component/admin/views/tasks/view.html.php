<?php
/**
 * @package      Projectfork
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.view');


class ProjectforkViewTasks extends JView
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $authors;
    protected $assigned;
    protected $tasklists;
    protected $milestones;
    protected $nulldate;


    /**
     * Display the view
     *
     */
    public function display($tpl = null)
    {
        // Get data from model
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state      = $this->get('State');
        $this->authors    = $this->get('Authors');
        $this->assigned   = $this->get('AssignedUsers');
        $this->tasklists  = $this->get('Tasklists');
        $this->milestones = $this->get('Milestones');
        $this->nulldate   = JFactory::getDbo()->getNullDate();

        // Check for errors
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        if ($this->getLayout() !== 'modal') $this->addToolbar();

        parent::display($tpl);
    }


    /**
     * Add the page title and toolbar.
     *
     */
    protected function addToolbar()
    {
        $access = ProjectforkHelper::getActions();

        JToolBarHelper::title(JText::_('COM_PROJECTFORK_TASKS_TITLE'), 'article.png');

        if ($access->get('task.create')) {
            JToolBarHelper::addNew('task.add');
        }

        if ($access->get('task.edit') || $access->get('task.edit.own')) {
            JToolBarHelper::editList('task.edit');
        }

        if ($access->get('task.edit.state')) {
            JToolBarHelper::divider();
            JToolBarHelper::publish('tasks.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('tasks.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
            JToolBarHelper::archiveList('tasks.archive');
            JToolBarHelper::checkin('tasks.checkin');
        }

        if ($this->state->get('filter.published') == -2 && $access->get('task.delete')) {
            JToolBarHelper::deleteList('', 'tasks.delete','JTOOLBAR_EMPTY_TRASH');
            JToolBarHelper::divider();
        }
        elseif ($canDo->get('core.edit.state') || $canDo->get('task.edit.state')) {
            JToolBarHelper::trash('tasks.trash');
            JToolBarHelper::divider();
        }
    }
}
