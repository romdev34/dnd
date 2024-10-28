<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Test\Unit\Block;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RomainDndOffers\Offers\Block\View;
use RomainDndOffers\Offers\Api\OfferRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Element\Template\Context;
use RomainDndOffers\Offers\Api\Data\OfferInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class ViewTest extends TestCase
{
    /** @var Object */
    private object $viewBlock;

    /** @var OfferRepositoryInterface|MockObject */
    private MockObject|OfferRepositoryInterface $offerRepository;

    /** @var RequestInterface|MockObject */
    private MockObject|RequestInterface $request;

    /** @var ManagerInterface|MockObject */
    private MockObject|ManagerInterface $messageManager;

    /** @var Redirect|MockObject */
    private Redirect|MockObject $resultRedirect;

    /** @var LoggerInterface|MockObject */
    private LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->offerRepository = $this->createMock(OfferRepositoryInterface::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->messageManager = $this->createMock(ManagerInterface::class);
        $this->resultRedirect = $this->createMock(Redirect::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $context = $this->createMock(Context::class);
        $context->method('getLogger')->willReturn($this->logger);

        $this->viewBlock = $objectManager->getObject(
            View::class,
            [
                'context' => $context,
                'offerRepository' => $this->offerRepository,
                'request' => $this->request,
                'resultRedirect' => $this->resultRedirect,
                'messageManager' => $this->messageManager
            ]
        );
    }

    public function testGetOfferById()
    {
        $offerId = 1;

        /** @var OfferInterface|MockObject $offer */
        $offer = $this->createMock(OfferInterface::class);

        $this->request->method('getParam')
            ->with('id')
            ->willReturn($offerId);

        $this->offerRepository->method('getById')
            ->with($offerId)
            ->willReturn($offer);

        $result = $this->viewBlock->getOfferById();

        $this->assertSame($offer, $result);
    }

    public function testGetOfferByIdNoSuchEntityException()
    {
        $offerId = 1;

        $this->request->method('getParam')
            ->with('id')
            ->willReturn($offerId);

        $this->offerRepository->method('getById')
            ->with($offerId)
            ->willThrowException(new NoSuchEntityException());

        $this->messageManager->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('The requested offer does not exist.'));

        $result = $this->viewBlock->getOfferById();

        $this->assertNull($result);
    }

    public function testGetOfferByIdNoParam()
    {
        $this->request->method('getParam')
            ->with('id')
            ->willReturn(null);

        $result = $this->viewBlock->getOfferById();

        $this->assertNull($result);
    }
}
