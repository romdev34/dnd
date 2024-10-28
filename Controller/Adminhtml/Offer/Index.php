<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * Constructor
     *
     * @param Context     $context
     * @param PageFactory $rawFactory
     */
    public function __construct(
        Context     $context,
        PageFactory $rawFactory
    ) {
        $this->pageFactory = $rawFactory;
        parent::__construct($context);
    }

    /**
     * Add the main Admin Grid page
     *
     * @return Page
     */
    public function execute(): Page
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('RomainDndOffers_Offers::offers_submenu');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Offers'));
        return $resultPage;
    }
}
