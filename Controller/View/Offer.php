<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Controller\View;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;

class Offer implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param PageFactory $pageFactory
     * @param RequestInterface $request
     */
    public function __construct(PageFactory $pageFactory, RequestInterface $request)
    {
        $this->pageFactory = $pageFactory;
        $this->request = $request;
    }

    /**
     * @inheritdoc
     */
    public function execute(): Page|ResultInterface|ResponseInterface
    {
        // Get the params that were passed from our Router
        $offerId = $this->request->getParam('id', null);
        $page = $this->pageFactory->create();
        $page->getLayout()->getBlock('offers.details');

        return  $page;
    }
}
