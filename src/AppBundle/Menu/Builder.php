<?php

/*
 * Nexxus Stock Keeping (online voorraad beheer software)
 * Copyright (C) 2018 Copiatek Scan & Computer Solution BV
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see licenses.
 *
 * Copiatek – info@copiatek.nl – Postbus 547 2501 CM Den Haag
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Builder
{    
    public function __construct(FactoryInterface $factory, AuthorizationChecker $authorizationChecker, TokenStorageInterface $tokenStorage) {
      $this->factory = $factory;
      $this->authorizationChecker = $authorizationChecker;
      $this->tokenStorage = $tokenStorage;
    }

    public function createMainMenu(array $options)
    {
        $role = $this->authorizationChecker;

        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        // add user menu items
        if($role->isGranted('ROLE_LOCAL') || $role->isGranted('ROLE_PARTNER')) {
            $menu->addChild('Dashboard', array('route' => 'home'));

            if($role->isGranted('ROLE_LOGISTICS')) {
                $menu->addChild('Logistiek', array('route' => 'logistics_calendar'));
            }

            if($role->isGranted('ROLE_LOCAL')) {
                $menu->addChild('Voorraad', array('route' => 'product_index'));
            }

            $menu->addChild('Inkoop', array('route' => 'purchaseorder_index'));
            $menu->addChild('Verkoop', array('route' => 'salesorder_index'));
            $menu->addChild('Klanten', array('route' => 'customer_index'));
            $menu->addChild('Leveranciers', array('route' => 'supplier_index'));
        }
        else {
            $menu->addChild('Portal', array('route' => 'home'));
        }

        return $menu;
    }

    public function createUserMenu(array $options)
    {
        $role = $this->authorizationChecker;
        $user = $this->tokenStorage->getToken()->getUser();

        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        if($role->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Admin', array('route' => 'admin_index'));
        }

        if($role->isGranted('ROLE_LOCAL') || $role->isGranted('ROLE_PARTNER'))
        {
            $menu->addChild('Help', array('route' => 'underconstruction'));
            $menu->addChild(strtoupper($user->getUsername()) . ': Logout', array('route' => 'fos_user_security_logout'));
        }
        else
        {
            $menu->addChild('Login', array('route' => 'fos_user_security_login'));
        }

        return $menu;
    }
}
