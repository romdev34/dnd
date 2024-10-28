<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Grid extends Container
{
    /**
     * Grid Block
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_controller = 'adminhtml_offers';
        $this->_blockGroup = 'RomainDndOffers_Offers';
        $this->_headerText = __('Manage Offers');
        $this->_addButtonLabel = __('Add New Offer');
        parent::_construct();
    }
}
