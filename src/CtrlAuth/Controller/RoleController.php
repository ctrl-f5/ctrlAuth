<?php

namespace CtrlAuth\Controller;

use Zend\View\Model\ViewModel;
use Ctrl\Controller\AbstractController;;
use CtrlAuth\View\Model\RoleEditView;
use CtrlAuth\Domain\Role;

class RoleController extends AbstractController
{
    CONST EVENT_ROLE_EDIT_LOAD = 'ctrl_auth_role_edit_load';

    public function indexAction()
    {
        /** @var $userService \CtrlAuth\Service\UserService */
        $userService = $this->getDomainService('CtrlAuthUser');
        $currentUser = $userService->getAuthenticatedUser();
        try {
            /** @var $user \CtrlAuth\Domain\User */
            $user = $userService->getById($this->params()->fromRoute('id'));
        } catch (\Exception $e) {
            $user = null;
        }

        /** @var $service \CtrlAuth\Service\RoleService */
        $service = $this->getDomainService('CtrlAuthRole');
        if ($user) {
            $roles = $service->getByUser($user);
        } else {
            $roles = $service->getAll();
        }

        return new ViewModel(array(
            'roles' => $roles,
            'user' => $user,
            'canEditRoles' => $currentUser->hasAccessTo('routes.CtrlAuth\Controller.Role.edit'),
            'canEditSystemRoles' => $currentUser->hasAccessTo('actions.CtrlAuth.Role.editSystemRole'),
        ));
    }

    public function editTabsAction()
    {
        $roleService = $this->getDomainService('CtrlAuthRole');
        $role = $roleService->getById($this->params()->fromRoute('role'));
        $activeSlug = $this->params()->fromRoute('slug');

        $event = $this->loadRoleEditPages($role, $activeSlug);
        $result = $event->getParam('result');

        if ($result instanceof \Zend\Http\Response) {
            return $result;
        }

        if ($result instanceof ViewModel) {
            $viewModel = new RoleEditView();
            $viewModel->addChild($result, 'pageContent');

            return $viewModel->setVariables(array(
                'role' => $role,
                'pages' => $event->getParam('pages'),
                'activeTab' => $activeSlug,
            ));
        }

        return $this->redirect()->toRouteWithError('The request could not be completed', 'ctrl_auth/default', array('controller' => 'role'));
    }

    /**
     * Configures the default pages in the EventManager
     * other components can add edit pages by hooking in the
     * EVENT_ROLE_EDIT_LOAD and adding your page to the pages event param
     *
     * @param $role
     * @param $activeSlug
     * @return \Zend\EventManager\Event
     */
    protected function loadRoleEditPages($role, $activeSlug)
    {
        // add main edit tab and remove tab
        $this->getEventManager()->attach(self::EVENT_ROLE_EDIT_LOAD, array($this, 'onRoleEditLoadDefaultPage'), 100);
        $this->getEventManager()->attach(self::EVENT_ROLE_EDIT_LOAD, array($this, 'onRoleEditLoadChildrenPage'), 99);
        $this->getEventManager()->attach(self::EVENT_ROLE_EDIT_LOAD, array($this, 'onRoleEditLoadRemovePage'), 0);

        // prepare the event to and trigger
        $event = new \Zend\EventManager\Event(self::EVENT_ROLE_EDIT_LOAD, $this, array(
            'role' => $role,
            'pages' => array(),
            'active-slug' => $activeSlug,
        ));
        $this->getEventManager()->trigger($event);

        $pages = $event->getParam('pages', array());

        // add a button to go back to the index page
        $slug = 'back';
        $pages[] = array(
            'slug' => $slug,
            'label' => 'back',
            'url' => $this->url()->fromRoute('ctrl_auth/default', array(
                'controller' => 'role',
            )),
        );
        $event->setParam('pages', $pages);

        return $event;
    }

    public function onRoleEditLoadDefaultPage(\Zend\EventManager\Event $e)
    {
        /** @var $role Role */
        $role = $e->getParam('role', array());
        $pages = $e->getParam('pages', array());
        $active = $e->getParam('active-slug', array());

        // a settings tab
        $slug = 'default';
        $pages[] = array(
            'slug' => $slug,
            'label' => 'settings',
            'url' => $this->url()->fromRoute('ctrl_auth/role_edit', array(
                'role' => $role->getId(),
                'slug' => $slug,
            )),
        );
        $e->setParam('pages', $pages);

        // if this page is currently active, set the page content ViewModel
        if ($slug == $active) {

            $form = new \CtrlAuth\Form\Role\Edit();
            $form->loadModel($role);

            $form->setAttribute('action', $this->url()->fromRoute('ctrl_auth/role_edit', array(
                'role' => $role->getId(),
                'page' => $slug,
            )));
            $form->setReturnUrl($this->url()->fromRoute('ctrl_auth/default', array(
                'controller' => 'role',
            )));

            if ($this->getRequest()->isPost()) {
                $form->setData($this->getRequest()->getPost());
                if ($form->isValid()) {
                    $roleService = $this->getDomainService('CtrlAuthRole');
                    $elems = $form->getElements();
                    $role->setName($elems[$form::ELEM_NAME]->getValue());
                    $role->setIsSystemRole(null !== $elems[$form::ELEM_SYSTEM]->getValue());
                    $roleService->persist($role);
                    $e->setParam('result', $this->redirect()->toUrl($form->getReturnurl()));
                    $e->stopPropagation(true);
                    return true;
                }
            }

            $view = new RoleEditView(array(
                'role' => $role,
                'form' => $form,
            ));
            $view->setTemplate('ctrl-auth/role/edit');
            $e->setParam('result', $view);
        }
    }

    public function onRoleEditLoadRemovePage(\Zend\EventManager\Event $event)
    {
        $role = $event->getParam('role', array());
        $pages = $event->getParam('pages', array());
        $active = $event->getParam('active-slug', array());

        // a settings tab
        $slug = 'remove';
        $pages[] = array(
            'slug' => $slug,
            'label' => 'remove',
            'url' => $this->url()->fromRoute('ctrl_auth/role_edit', array(
                'role' => $role->getId(),
                'slug' => $slug,
            )),
        );
        $event->setParam('pages', $pages);

        // if this page is currently active, set the page content ViewModel
        if ($slug == $active) {
            if ($this->params()->fromQuery('remove-do') == 1) {
                try {

                    $roleService = $this->getDomainService('CtrlAuthRole');
                    $roleService->remove($role);
                    $roleService->flush($role);
                    $event->setParam('result', $this->redirect()->toRoute('ctrl_auth/default', array(
                        'controller' => 'role',
                    )));

                } catch (\Exception $ex) {

                    $this->flashMessenger()->setNamespace('debug')->addMessage($ex->getMessage());
                    $event->setParam('result', $this->redirect()->toRouteWithError('Failed to remove this entity', 'ctrl_auth/role_edit', array(
                        'role' => $role->getId(),
                        'slug' => 'remove',
                    )));

                }
                $event->stopPropagation(true);
                return true;
            }

            $view = new RoleEditView(array(
                'role' => $role,
            ));
            $view->setTemplate('ctrl-auth/role/remove');
            $event->setParam('result', $view);
        }
    }

    public function onRoleEditLoadChildrenPage(\Zend\EventManager\Event $event)
    {
        $role = $event->getParam('role', array());
        $pages = $event->getParam('pages', array());
        $active = $event->getParam('active-slug', array());

        // a settings tab
        $slug = 'children';
        $pages[] = array(
            'slug' => $slug,
            'label' => 'children',
            'url' => $this->url()->fromRoute('ctrl_auth/role_edit', array(
                'role' => $role->getId(),
                'slug' => $slug,
            )),
        );
        $event->setParam('pages', $pages);

        // if this page is currently active, set the page content ViewModel
        if ($slug == $active) {
            /** @var $roleService \CtrlAuth\Service\RoleService */
            $roleService = $this->getDomainService('CtrlAuthRole');

            if ($this->params()->fromQuery('remove-do') == 1) {
                try {

                    $roleService->remove($role);
                    $roleService->flush($role);
                    $event->setParam('result', $this->redirect()->toRoute('ctrl_auth/default', array(
                        'controller' => 'role',
                    )));

                } catch (\Exception $ex) {

                    $this->flashMessenger()->setNamespace('debug')->addMessage($ex->getMessage());
                    $event->setParam('result', $this->redirect()->toRouteWithError('Failed to remove this entity', 'ctrl_auth/role_edit', array(
                        'role' => $role->getId(),
                        'slug' => 'remove',
                    )));

                }
                $event->stopPropagation(true);
                return true;
            }

            $view = new RoleEditView(array(
                'role' => $role,
                'possibleParents' => $roleService->getPossibleParentRoles($role),
            ));
            $view->setTemplate('ctrl-auth/role/children');
            $event->setParam('result', $view);
        }
    }

    public function moveParentAction()
    {
        $roleService = $this->getDomainService('CtrlAuthRole');
        /** @var $role Role */
        $role = $roleService->getById($this->params()->fromRoute('id'));

        $pm = false;
        foreach ($role->getParentMaps() as $pmap) {
            if ($this->params()->fromQuery('parent') == $pmap->getParent()->getId()) {
                $pm = $pmap;
            }
        }
        $newMaps = new \CtrlAuth\Domain\Collection($role->getParentMaps()->toArray());
        $newMaps->moveOrderInCollection(
            $pm->getId(),
            $this->params()->fromQuery('direction')
        );

        $role->setParents($newMaps);
        $roleService->persist($role);

        return $this->redirect()->toRoute('ctrl_auth/default', array(
            'controller' => 'role',
        ));
    }
}
