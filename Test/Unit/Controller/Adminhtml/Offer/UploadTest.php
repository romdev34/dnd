<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Test\Unit\Controller\Adminhtml\Offer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RomainDndOffers\Offers\Controller\Adminhtml\Offer\Upload;
use Magento\Backend\App\Action\Context;
use RomainDndOffers\Offers\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\Request\Http;

class UploadTest extends TestCase
{
    /** @var Upload */
    private Upload $uploadController;

    /** @var MockObject */
    private MockObject $context;

    /** @var MockObject */
    private MockObject $imageUploader;

    /** @var MockObject */
    private MockObject $resultFactory;

    /** @var MockObject */
    private MockObject $request;

    /** @var MockObject */
    private MockObject $resultJson;

    public function testExecuteWithValidData()
    {
        $imageId = 'image';
        $result = ['success' => true];

        $this->request->method('getParam')->with('param_name', 'image')->willReturn($imageId);
        $this->imageUploader->method('saveFileToTmpDir')->with($imageId)->willReturn($result);
        $this->resultFactory->method('create')->with(ResultFactory::TYPE_JSON)->willReturn($this->resultJson);
        $this->resultJson->expects($this->once())->method('setData')->with($result)->willReturnSelf();

        $response = $this->uploadController->execute();

        $this->assertSame($this->resultJson, $response);
    }

    public function testExecuteWithException()
    {
        $imageId = 'image';
        $exceptionMessage = 'Some error';
        $exceptionCode = 500;
        $result = ['error' => $exceptionMessage, 'errorcode' => $exceptionCode];

        $this->request->method('getParam')->with('param_name', 'image')->willReturn($imageId);
        $this->imageUploader->method('saveFileToTmpDir')->with($imageId)->willThrowException(
            new \Exception($exceptionMessage, $exceptionCode)
        );
        $this->resultFactory->method('create')->with(ResultFactory::TYPE_JSON)->willReturn($this->resultJson);
        $this->resultJson->expects($this->once())->method('setData')->with($result)->willReturnSelf();

        $response = $this->uploadController->execute();

        $this->assertSame($this->resultJson, $response);
    }

    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->imageUploader = $this->createMock(ImageUploader::class);
        $this->resultFactory = $this->createMock(ResultFactory::class);
        $this->request = $this->createMock(Http::class);
        $this->resultJson = $this->createMock(Json::class);

        $this->context->method('getRequest')->willReturn($this->request);
        $this->context->method('getResultFactory')->willReturn($this->resultFactory);

        $this->uploadController = new Upload(
            $this->context,
            $this->imageUploader
        );
    }
}
