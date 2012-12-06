<?php
namespace CtrlTest\Domain;

use CtrlTest\DbTestCase;
use CtrlAuth\Domain\Role;
use Zend\Permissions\Acl\Acl;

class RoleTest extends DbTestCase
{
    /**
     * @var Acl
     */
    protected $acl;

    protected function setup()
    {
        $this->acl = new Acl();
    }

    /**
     * creating resource tree:
     *      - set-1
     *          - var-1-1
     *          - var-1-2
     *          - set-2
     *      - set-2
     *          - var-2-1
     *          - var-2-2
     *          - set-2-1
     *              - var-2-1-1
     *              - var-2-1-2
     *
     * @param Acl $acl
     */
    protected function createResourceTree(Acl $acl)
    {
        $acl->addResource('set-1');
        $acl->addResource('var-1-1', 'set-1');
        $acl->addResource('var-1-2', 'set-1');
        $acl->addResource('set-2');
        $acl->addResource('var-2-1', 'set-2');
        $acl->addResource('var-2-2', 'set-2');
        $acl->addResource('set-2-1');
        $acl->addResource('var-2-1-1', 'set-2-1');
        $acl->addResource('var-2-1-2', 'set-2-1');
    }

    /**
     * creating resource tree:
     *      - controllers
     *          - con-1
     *              - act-1-1
     *              - act-1-2
     *          - con-2
     *              - act-2-1
     *              - act-2-2
     *
     * @param Acl $acl
     */
    protected function createResourceActionTree(Acl $acl)
    {
        $acl->addResource('controllers');
        $acl->addResource('con-1', 'controllers');
        $acl->addResource('act-1-1', 'con-1');
        $acl->addResource('act-1-2', 'con-1');
        $acl->addResource('con-2', 'controllers');
        $acl->addResource('act-2-1', 'con-2');
        $acl->addResource('act-2-2', 'con-2');
    }

    /**
     * creating role tree:
     *      - blog
     *          - blog-viewer
     *          - blog-editor
     *          - blog-admin
     *              - blog-sysadmin
     *      - auth
     *          - auth-admin
     *              - auth-sysadmin
     *
     *      - admin
     *          - blog-sysadmin
     *          - auth-sysadmin
     *
     * @param Acl $acl
     */
    protected function createRoleTree(Acl $acl)
    {
        // blog roles
        $acl->addRole('blog');
        $acl->addRole('blog-viewer', array('blog'));
        $acl->addRole('blog-editor', array('blog'));
        $acl->addRole('blog-admin', array('blog', 'blog-editor', 'blog-viewer'));
        $acl->addRole('blog-admin-sysadmin', array('blog', 'blog-admin'));
        // auth roles
        $acl->addRole('auth');
        $acl->addRole('auth-admin', array('auth'));
        $acl->addRole('auth-admin-sysadmin', array('auth', 'auth-admin'));

        $acl->addRole('admin', array('auth-admin-sysadmin', 'blog-admin-sysadmin'));
    }

    protected function breakdown()
    {
        $this->acl = null;
    }

    public function testDeniesByDefault()
    {
        $this->createResourceTree($this->acl);
        $this->acl->addRole('role1');

        $this->assertTrue( ! $this->acl->isAllowed('role1', 'var-2-1'));
    }

    public function testCanAllowResource()
    {
        $this->createResourceTree($this->acl);
        $this->acl->addRole('role1');

        $this->assertTrue( ! $this->acl->isAllowed('role1', 'var-2-1-2'));

        $this->acl->allow('role1', 'var-2-1-2');

        $this->assertTrue($this->acl->isAllowed('role1', 'var-2-1-2'));
    }

    public function testCanAllowResourceTree()
    {
        $this->createResourceTree($this->acl);
        $this->acl->addRole('role1');

        $this->assertTrue( ! $this->acl->isAllowed('role1', 'var-2-1-2'));

        $this->acl->allow('role1', 'set-2-1');

        $this->assertTrue($this->acl->isAllowed('role1', 'var-2-1-2'));
    }

    public function testCanAllowRoleTree()
    {
        $this->createResourceTree($this->acl);
        $this->createRoleTree($this->acl);

        $this->acl->allow('blog-viewer', 'var-2-1-2');

        $this->assertTrue($this->acl->isAllowed('blog-viewer', 'var-2-1-2'));
        $this->assertTrue( ! $this->acl->isAllowed('blog', 'var-2-1-2'));

        $this->acl->allow('blog-admin', 'var-2-1-1');

        $this->assertTrue($this->acl->isAllowed('blog-admin', 'var-2-1-1'));
        $this->assertTrue($this->acl->isAllowed('blog-admin-sysadmin', 'var-2-1-1'));
        $this->assertTrue( ! $this->acl->isAllowed('blog', 'var-2-1-1'));
        $this->assertTrue( ! $this->acl->isAllowed('blog-viewer', 'var-2-1-1'));
        $this->assertTrue( ! $this->acl->isAllowed('blog-editor', 'var-2-1-1'));

        $this->assertTrue($this->acl->isAllowed('admin', 'var-2-1-1'));
    }

    public function testCanAllowRolesAndResourceTrees()
    {
        $this->createResourceActionTree($this->acl);
        $this->createRoleTree($this->acl);

        $this->acl->allow('blog-viewer', 'act-1-1');
        $this->acl->allow('blog-admin', 'act-1-2');
        $this->acl->allow('blog-admin-sysadmin', 'con-1');

        $this->assertTrue( ! $this->acl->isAllowed('blog-viewer', 'con-1'));
        $this->assertTrue( ! $this->acl->isAllowed('blog-admin', 'con-1'));
        $this->assertTrue($this->acl->isAllowed('blog-admin-sysadmin', 'con-1'));

        $this->assertTrue( ! $this->acl->isAllowed('blog-viewer', 'act-1-2'));
        $this->assertTrue($this->acl->isAllowed('blog-admin', 'act-1-2'));
        $this->assertTrue($this->acl->isAllowed('blog-admin-sysadmin', 'act-1-2'));

        $this->assertTrue($this->acl->isAllowed('blog-viewer', 'act-1-1'));
        $this->assertTrue($this->acl->isAllowed('blog-admin', 'act-1-1'));
        $this->assertTrue($this->acl->isAllowed('blog-admin-sysadmin', 'act-1-1'));
    }

    public function testInheritParentsParents()
    {
        $this->createResourceActionTree($this->acl);
        $this->createRoleTree($this->acl);

        $this->acl->allow('blog-viewer', 'act-1-1');

        $this->assertTrue($this->acl->isAllowed('blog-admin-sysadmin', 'act-1-1'));
    }
}
