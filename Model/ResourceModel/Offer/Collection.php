<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Model\ResourceModel\Offer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use RomainDndOffers\Offers\Model\Offer as OfferModel;
use RomainDndOffers\Offers\Model\ResourceModel\Offer as OfferResourceModel;

class Collection extends AbstractCollection
{
    /**
     * Define model and resource model
     */
    protected function _construct(): void
    {
        $this->_init(OfferModel::class, OfferResourceModel::class);
    }
}
