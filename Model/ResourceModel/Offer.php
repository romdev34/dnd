<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Offer extends AbstractDb
{
    /**
     * Define main table and primary key
     */
    protected function _construct(): void
    {
        // Initialize the table 'dnd_offers' and its primary key 'offer_id'
        $this->_init('dnd_offers', 'offer_id');
    }
}
