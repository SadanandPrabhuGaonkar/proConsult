<?php

namespace Application\Concrete\User;
class User extends \Concrete\Core\User\User
{

    public function isClientAdmin()
    {
        return array_search( Config::get('concrete.client_admin_group_name') , $this->getUserGroups(), true ) !== false;
    }
}
