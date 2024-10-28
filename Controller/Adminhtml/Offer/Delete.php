<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Controller\Adminhtml\Offer;

use RomainDndOffers\Offers\Model\Offer;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;

/**
 * Delete CMS page action.
 */
class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'RomainDndOffers_Offers::offres';

    /**
     * Delete action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('offer_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create(Offer::class);
                $model->load($id);

                $label = $model->getLabel();
                $model->delete();

                // display success message
                $this->messageManager->addSuccessMessage(__('The offer ' . $label . ' has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['offer_id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find an offer to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
