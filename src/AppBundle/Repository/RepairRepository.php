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

namespace AppBundle\Repository;

use AppBundle\Entity\Repair;
use AppBundle\Entity\SalesService;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductOrderRelation;

class RepairRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param int $priceTrigger
     */
    public function generateBaseServices(Repair $repair)
    {
        $order = $repair->getOrder();
        $product = new Product();
        $product->setName("Product to repair");
        $product->setLocation($order->getLocation());
        $product->setSku(time());
        $this->_em->persist($product);

        $productOrderRelation = new ProductOrderRelation($product, $order);
        $productOrderRelation->setQuantity(1);
        $this->_em->persist($productOrderRelation);

        $research = new SalesService($productOrderRelation);
        $research->setDescription("Research");
        $this->_em->persist($research);

        $backup = new SalesService($productOrderRelation);
        $backup->setDescription("Backup");
        $this->_em->persist($backup);

        $replacement = new SalesService($productOrderRelation);
        $replacement->setDescription("Replacement");
        $this->_em->persist($replacement);

        $repair = new SalesService($productOrderRelation);
        $repair->setDescription("Repair till €50,--");
        $this->_em->persist($repair);
    }
}
