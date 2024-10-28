<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Test\Unit\Controller\View;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RomainDndOffers\Offers\Controller\View\Offer;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\LayoutInterface;

class OfferTest extends TestCase
{
    /** @var Offer */
    private Offer $offerController;

    /** @var MockObject */
    private MockObject $pageFactory;

    /** @var MockObject */
    private MockObject $request;

    /** @var MockObject */
    private MockObject $page;

    /** @var MockObject */
    private MockObject $layout;

    protected function setUp(): void
    {
        $this->pageFactory = $this->createMock(PageFactory::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->page = $this->createMock(Page::class);
        $this->layout = $this->createMock(LayoutInterface::class);

        $this->pageFactory->method('create')->willReturn($this->page);
        $this->page->method('getLayout')->willReturn($this->layout);

        $this->offerController = new Offer(
            $this->pageFactory,
            $this->request
        );
    }

    public function testExecute()
    {
        $offerId = '123';
        $this->request->method('getParam')->with('id', null)->willReturn($offerId);

        $this->layout->expects($this->once())->method('getBlock')->with('offers.details');

        $result = $this->offerController->execute();

        $this->assertInstanceOf(Page::class, $result);
    }
}
