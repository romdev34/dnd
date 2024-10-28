<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Block\Adminhtml\Edit;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve the data configuration for a button.
     *
     * @return array<string, mixed> The button configuration data.
     * @throws LocalizedException
     */
    public function getButtonData(): array
    {
        $data = [];
        if ($this->getOfferId()) {
            $data = [
                'label' => __('Delete Offer'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * URL to send delete requests to.
     *
     * @return string
     * @throws LocalizedException
     */
    public function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['offer_id' => $this->getOfferId()]);
    }
}
