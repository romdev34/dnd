<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use RomainDndOffers\Offers\Model\OfferFactory;

class Edit extends Action
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var OfferFactory
     */
    protected OfferFactory $offerFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param OfferFactory $offerFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        OfferFactory $offerFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->offerFactory = $offerFactory;
        parent::__construct($context);
    }

    /**
     * Edit Offer action
     *
     * @return ResultInterface|Page
     */
    public function execute(): ResultInterface|Page
    {
        // Get ID of the offer to be edited
        $offerId = $this->getRequest()->getParam('offer_id');

        $offer = $this->offerFactory->create();

        if ($offerId) {
            $offer->load($offerId);
            if (!$offer->getId()) {
                $this->messageManager->addErrorMessage(__('This offer no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        // Register the offer model to use it in the form
        $this->coreRegistry->register('dnd_offers_offer', $offer);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $offerId ? __('Edit Offer') : __('New Offer'),
            $offerId ? __('Edit Offer') : __('New Offer')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Offers'));
        $resultPage->getConfig()->getTitle()->prepend($offer->getId() ? $offer->getLabel() : __('New Offer'));

        return $resultPage;
    }

    /**
     * Initialize the page layout with breadcrumbs
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage): Page
    {
        $resultPage->setActiveMenu('RomainDndOffers_Offers::offers')
            ->addBreadcrumb(__('Offers'), __('Offers'))
            ->addBreadcrumb(__('Manage Offers'), __('Manage Offers'));
        return $resultPage;
    }
}
