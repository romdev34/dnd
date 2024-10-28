<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Test\Unit\Controller\Adminhtml\Offer;

use RomainDndOffers\Offers\Api\OfferRepositoryInterface;
use RomainDndOffers\Offers\Controller\Adminhtml\Offer\Save;
use RomainDndOffers\Offers\Model\ImageUploader;
use RomainDndOffers\Offers\Model\Offer;
use RomainDndOffers\Offers\Model\OfferFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SaveTest extends TestCase
{
    /** @var Save */
    private Save $saveController;

    /** @var MockObject */
    private MockObject $context;

    /** @var MockObject */
    private MockObject $dataPersistor;

    /** @var MockObject */
    private MockObject $offerFactory;

    /** @var MockObject */
    private MockObject $offerRepository;

    /** @var MockObject */
    private MockObject $imageUploader;

    /** @var MockObject */
    private MockObject $serializer;

    /** @var MockObject */
    private MockObject $request;

    /** @var MockObject */
    private MockObject $messageManager;

    /** @var MockObject */
    private MockObject $resultRedirectFactory;

    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->dataPersistor = $this->createMock(DataPersistorInterface::class);
        $this->offerFactory = $this->createMock(OfferFactory::class);
        $this->offerRepository = $this->createMock(OfferRepositoryInterface::class);
        $this->imageUploader = $this->createMock(ImageUploader::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->request = $this->createMock(Http::class);
        $this->messageManager = $this->createMock(ManagerInterface::class);
        $this->resultRedirectFactory = $this->createMock(RedirectFactory::class);

        $this->context->method('getRequest')->willReturn($this->request);
        $this->context->method('getResultRedirectFactory')->willReturn($this->resultRedirectFactory);
        $this->context->method('getMessageManager')->willReturn($this->messageManager);

        $this->saveController = new Save(
            $this->context,
            $this->dataPersistor,
            $this->offerFactory,
            $this->offerRepository,
            $this->imageUploader,
            $this->serializer
        );
    }

    /**
     * @throws LocalizedException
     */
    public function testExecuteWithValidData()
    {
        // Define mocks and their behaviors
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        $offerId = 1;
        $imageData[0] = ['file' => 'image.jpg'];

        $uri = '*/*/';

        $offerMock = $this->createMock(Offer::class);
        $redirectMock = $this->createMock(Redirect::class);

        $this->request->method('getPostValue')->willReturn($data);
        $this->request->method('getParam')->with('offer_id')->willReturn($offerId);

        $this->offerFactory->method('create')->willReturn($offerMock);
        $this->offerRepository->method('getById')->with($offerId)->willReturn($offerMock);
        $this->offerRepository->method('save')->with($offerMock);

        $this->resultRedirectFactory->method('create')->willReturn($redirectMock);

        $offerMock->method('getImage')->willReturn($imageData);
        $offerMock->method('getCategories')->willReturn(['value']);
        $offerMock->method('getStartDate')->willReturn('2024-01-01');
        $offerMock->method('getEndDate')->willReturn('2024-01-01');
        $offerMock->method('setData')->with($data);

        $this->messageManager->method('addSuccessMessage')->with(__('You saved the offer.'));
        $this->dataPersistor->method('clear')->with('offer');
        $redirectMock->expects($this->once())
            ->method('setPath')
            ->willReturnSelf();

        // Call the method under test
        $result = $this->saveController->execute();
        // Assert
        $this->assertInstanceOf(Redirect::class, $result);
    }

    public function testExecuteWithException()
    {
        // Define mocks and their behaviors
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        $offerId = 1;
        $exceptionMessage = 'Some exception';

        $redirectPath = '*/*/edit';

        $offerMock = $this->createMock(Offer::class);
        $redirectMock = $this->createMock(Redirect::class);

        $this->request->method('getPostValue')->willReturn($data);
        $this->request->method('getParam')->with('offer_id')->willReturn($offerId);

        $this->offerFactory->method('create')->willReturn($offerMock);
        $this->offerRepository->method('getById')->with($offerId)->willReturn($offerMock);
        $this->offerRepository->method('save')->with($offerMock)->willThrowException(new \Exception($exceptionMessage));

        $this->resultRedirectFactory->method('create')->willReturn($redirectMock);

        $offerMock->method('setData')->with($data);

        $this->messageManager->method('addErrorMessage')->with($exceptionMessage);
        $this->dataPersistor->method('set')->with('offer', $data);
        $redirectMock->method('setPath')->with($redirectPath, ['offer_id' => $offerId])->willReturnSelf();

        $result = $this->saveController->execute();

        $this->assertInstanceOf(Redirect::class, $result);
    }

    public function testExecuteWithNoData()
    {
        $redirectMock = $this->createMock(Redirect::class);

        $this->request->method('getPostValue')->willReturn(null);
        $this->resultRedirectFactory->method('create')->willReturn($redirectMock);

        $redirectMock->method('setPath')->with('*/*/')->willReturnSelf();

        $result = $this->saveController->execute();

        $this->assertInstanceOf(Redirect::class, $result);
    }
}
