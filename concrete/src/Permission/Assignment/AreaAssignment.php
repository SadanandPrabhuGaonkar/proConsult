<?php

namespace Concrete\Core\Permission\Assignment;

use Concrete\Core\Area\Area;
use Concrete\Core\Area\SubArea;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Stack\Stack;
use Concrete\Core\Permission\Access\Access;
use Concrete\Core\Permission\Key\Key as PermissionKey;
use Concrete\Core\Support\Facade\Application;

class AreaAssignment extends Assignment
{
    /**
     * @unused
     *
     * @var mixed
     */
    protected $area;

    protected $permissionObjectToCheck;

    /**
     * Mapping between area permissions (keys) and page permissions (values) when an area inherit permissions.
     *
     * @var array
     */
    protected $inheritedPermissions = [
        'view_area' => 'view_page',
        'edit_area_contents' => 'edit_page_contents',
        'add_layout_to_area' => 'edit_page_contents',
        'edit_area_design' => 'edit_page_contents',
        'edit_area_permissions' => 'edit_page_permissions',
        'schedule_area_contents_guest_access' => 'schedule_page_contents_guest_access',
        'delete_area_contents' => 'edit_page_contents',
    ];

    /**
     * Mapping between area permissions (keys) and block type permissions (values) when an area inherit permissions.
     *
     * @var array
     */
    protected $blockTypeInheritedPermissions = [
        'add_block_to_area' => 'add_block',
        'add_stack_to_area' => 'add_stack',
    ];

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Permission\Assignment\Assignment::setPermissionObject()
     *
     * @param \Concrete\Core\Area\Area $a
     */
    public function setPermissionObject($a)
    {
        if ($a->isGlobalArea()) {
            $cx = Stack::getByName($a->getAreaHandle(), 'ACTIVE');
            $a = Area::get($cx, STACKS_AREA_NAME);
            if (!$a) {
                return false;
            }
        }

        if ($a instanceof SubArea && !$a->overrideCollectionPermissions()) {
            $a = $a->getSubAreaParentPermissionsObject();
        }
        $this->permissionObject = $a;

        // if the area overrides the collection permissions explicitly (with a one on the override column) we check
        if ($a->overrideCollectionPermissions()) {
            $this->permissionObjectToCheck = $a;
        } else {
            if ($a->getAreaCollectionInheritID() > 0) {
                // in theory we're supposed to be inheriting some permissions from an area with the same handle,
                // set on the collection id specified above (inheritid). however, if someone's come along and
                // reverted that area to the page's permissions, there won't be any permissions, and we
                // won't see anything. so we have to check
                $areac = Page::getByID($a->getAreaCollectionInheritID());
                $inheritArea = Area::get($areac, $a->getAreaHandle());
                if ($inheritArea && $inheritArea->overrideCollectionPermissions()) {
                    // okay, so that area is still around, still has set permissions on it. So we
                    // pass our current area to our grouplist, userinfolist objects, knowing that they will
                    // smartly inherit the correct items.
                    $this->permissionObjectToCheck = $inheritArea;
                }
            }

            if (!$this->permissionObjectToCheck) {
                $this->permissionObjectToCheck = $a->getAreaCollectionObject();
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Permission\Assignment\Assignment::getPermissionAccessObject()
     */
    public function getPermissionAccessObject()
    {
        if ($this->permissionObjectToCheck instanceof Area) {
            $app = Application::getFacadeApplication();
            $db = $app->make(Connection::class);
            $r = $db->fetchColumn(
                'select paID from AreaPermissionAssignments where cID = ? and arHandle = ? and pkID = ? ',
                [
                    $this->permissionObjectToCheck->getCollectionID(),
                    $this->permissionObjectToCheck->getAreaHandle(),
                    $this->pk->getPermissionKeyID(),
                ]
            );
            if ($r) {
                return Access::getByID($r, $this->pk, false);
            }
        } elseif (isset($this->inheritedPermissions[$this->pk->getPermissionKeyHandle()])) {
            // this is a page
            $pk = PermissionKey::getByHandle($this->inheritedPermissions[$this->pk->getPermissionKeyHandle()]);
            $pk->setPermissionObject($this->permissionObjectToCheck);
            $pae = $pk->getPermissionAccessObject();

            return $pae;
        } elseif (isset($this->blockTypeInheritedPermissions[$this->pk->getPermissionKeyHandle()])) {
            $pk = PermissionKey::getByHandle($this->blockTypeInheritedPermissions[$this->pk->getPermissionKeyHandle()]);
            $pae = $pk->getPermissionAccessObject();

            return $pae;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Permission\Assignment\Assignment::getPermissionKeyToolsURL()
     */
    public function getPermissionKeyToolsURL($task = false)
    {
        $area = $this->getPermissionObject();
        $c = $area->getAreaCollectionObject();

        return parent::getPermissionKeyToolsURL($task) . '&cID=' . $c->getCollectionID() . '&arHandle=' . urlencode($area->getAreaHandle());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Permission\Assignment\Assignment::clearPermissionAssignment()
     */
    public function clearPermissionAssignment()
    {
        $app = Application::getFacadeApplication();
        $db = $app->make(Connection::class);
        $area = $this->getPermissionObject();
        $c = $area->getAreaCollectionObject();
        $db->executeQuery('update AreaPermissionAssignments set paID = 0 where pkID = ? and cID = ? and arHandle = ?', [$this->pk->getPermissionKeyID(), $c->getCollectionID(), $area->getAreaHandle()]);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Permission\Assignment\Assignment::assignPermissionAccess()
     */
    public function assignPermissionAccess(Access $pa)
    {
        $app = Application::getFacadeApplication();
        $db = $app->make(Connection::class);
        $db->replace(
            'AreaPermissionAssignments',
            [
                'cID' => $this->getPermissionObject()->getCollectionID(),
                'arHandle' => $this->getPermissionObject()->getAreaHandle(),
                'paID' => $pa->getPermissionAccessID(),
                'pkID' => $this->pk->getPermissionKeyID(),
            ],
            ['cID', 'arHandle', 'pkID'],
            true
        );
        $pa->markAsInUse();
    }
}
