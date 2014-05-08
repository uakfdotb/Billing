<?php

namespace App\ClientBundle;

final class ClientEvents
{
    /**
     * The create.admin event is thrown eachtime someone goes for the first time on a tenant's subdomain
     *
     * The event listener receives an
     * App\ClientBundle\Event\CreateAdminEvent instance.
     *
     * @var string
     */
    const CREATE_ADMIN = 'create.admin';
}