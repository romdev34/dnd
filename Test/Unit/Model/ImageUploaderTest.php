<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Test\Unit\Model;

use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RomainDndOffers\Offers\Model\ImageUploader;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\File\Name;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Store\Model\Store;

class ImageUploaderTest extends TestCase
{
    /**
     * @var ImageUploader
     */
    private ImageUploader $imageUploader;
    /**
     * @var Database|MockObject
     */
    private Database|MockObject $coreFileStorageDatabase;
    /**
     * @var MockObject|WriteInterface
     */
    private MockObject|WriteInterface $mediaDirectory;
    /**
     * @var MockObject|UploaderFactory
     */
    private MockObject|UploaderFactory $uploaderFactory;
    /**
     * @var StoreManagerInterface|MockObject
     */
    private StoreManagerInterface|MockObject $storeManager;
    /**
     * @var MockObject|Store
     */
    private MockObject|Store $store;
    /**
     * @var MockObject|LoggerInterface
     */
    private MockObject|LoggerInterface $logger;
    /**
     * @var MockObject|File
     */
    private MockObject|File $file;
    /**
     * @var MockObject|Name
     */
    private MockObject|Name $fileNameLookup;

    /**
     * @throws LocalizedException
     */
    public function testSaveFileToTmpDir()
    {
        $fileId = 'image';
        $uploader = $this->createMock(Uploader::class);
        $result = [
            "name" => "image.png",
            "full_path" => "image.png",
            "type" => "image/png",
            "tmp_name" => "/tmp/tmpimage39",
            "error" => 0,
            "size" => 37396,
            "file" => "image.png",
            "url" => "https://magento.dev.local/media/offer/tmp/images/image.png",
        ];

        $this->uploaderFactory->method('create')->with(['fileId' => $fileId])->willReturn($uploader);
        $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
        $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png'];

        $uploader->expects($this->once())->method('setAllowedExtensions')->with($allowedExtensions);
        $uploader->expects($this->once())->method('setAllowRenameFiles')->with(true);
        $uploader->expects($this->any())->method('setFilesDispersion')->with(true);
        $uploader->expects($this->once())->method('checkMimeType')->with($allowedMimeTypes)->willReturn(true);
        $uploader->expects($this->once())->method('save')->with('/var/www/pub/media/offer/tmp/images')->willReturn(
            $result
        );
        $this->mediaDirectory->expects($this->once())->method('getAbsolutePath')->with('offer/tmp/images')->willReturn(
            '/var/www/pub/media/offer/tmp/images'
        );
        $this->storeManager->expects($this->once())->method('getStore')->willReturn($this->storeManager);
        $this->coreFileStorageDatabase->expects($this->once())->method('saveFile')->willReturn(null);

        $this->assertEquals($result, $this->imageUploader->saveFileToTmpDir($fileId));
    }

    /**
     * @throws LocalizedException
     * @throws FileSystemException
     */
    public function testMoveFileFromTmp()
    {
        $imageName = '/image.png';
        $baseTmpPath = $this->imageUploader->getBaseTmpPath() . $imageName;
        $basePath = $this->imageUploader->getBasePath() . $imageName;
        $this->mediaDirectory->expects($this->atLeast(1))->method('getAbsolutePath')->with($basePath)->willReturn(
            '/var/www/pub/media/' . $basePath
        );
        $this->fileNameLookup->expects($this->atLeast(1))->method('getNewFileName')->with(
            '/var/www/pub/media/' . $basePath
        )->willReturn('image.png');

        $this->imageUploader->moveFileFromTmp($imageName);
        $this->assertFileDoesNotExist($baseTmpPath);
    }

    /**
     * @throws LocalizedException
     */
    public function testMoveFileFromTmpThrowsLocalizedException()
    {
        $imageName = '/image.png';
        $baseTmpPath = $this->imageUploader->getBaseTmpPath() . $imageName;
        $basePath = $this->imageUploader->getBasePath() . $imageName;

        $this->mediaDirectory->expects($this->once())->method('getAbsolutePath')->willReturn(
            '/var/www/pub/media/offer/images/image.png'
        );
        $this->fileNameLookup->expects($this->once())->method('getNewFileName')->with(
            '/var/www/pub/media/offer/images/image.png'
        )->willReturn($imageName);
        $this->mediaDirectory->expects($this->once())->method('renameFile')->with(
            $baseTmpPath,
            $basePath
        )->willThrowException(new LocalizedException(__('Localized exception')));
        $this->coreFileStorageDatabase->expects($this->once())->method('renameFile')->with($baseTmpPath, $basePath);

        $this->expectException(LocalizedException::class);
        $this->imageUploader->moveFileFromTmp($imageName);
    }

    /**
     * @throws FileSystemException
     */
    public function testMovable()
    {
        $imageName = '/image.png';
        $baseTmpPath = $this->imageUploader->getBaseTmpPath() . $imageName;

        $this->mediaDirectory->expects($this->atLeast(1))->method('getAbsolutePath')->with(
            'offer/tmp/images/image.png'
        )->willReturn('/var/www/pub/media/' . $baseTmpPath);
        $this->file->expects($this->once())->method('isExists')->willReturn(true);
        $isMovable = $this->imageUploader->movable($imageName);
        $this->assertTrue($isMovable);
    }

    /**
     * @throws FileSystemException
     */
    public function testMovableWithInvalidExtension()
    {
        $imageName = '/image.png';
        $baseTmpPath = $this->imageUploader->getBaseTmpPath() . '/image.ttt';
        $this->mediaDirectory->expects($this->atLeast(1))->method('getAbsolutePath')->with(
            'offer/tmp/images/image.png'
        )->willReturn('/var/www/pub/media/' . $baseTmpPath);
        $this->file->expects($this->once())->method('isExists')->willReturn(false);
        $isMovable = $this->imageUploader->movable($imageName);
        $this->assertFalse($isMovable);
    }

    /**
     * @throws FileSystemException
     */
    protected function setUp(): void
    {
        $this->coreFileStorageDatabase = $this->createMock(Database::class);
        $this->mediaDirectory = $this->createMock(WriteInterface::class);

        $this->uploaderFactory = $this->createMock(UploaderFactory::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->store = $this->createMock(Store::class);
        $this->storeManager->method('getStore')->willReturn($this->store);
        $this->store->method('getBaseUrl')
            ->with('media')
            ->willReturn('https://magento.dev.local/media/');
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->file = $this->createMock(File::class);
        $this->fileNameLookup = $this->createMock(Name::class);
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->method('getDirectoryWrite')->with(DirectoryList::MEDIA)->willReturn($this->mediaDirectory);
        $this->imageUploader = new ImageUploader(
            $this->coreFileStorageDatabase,
            $filesystem,
            $this->uploaderFactory,
            $this->storeManager,
            $this->logger,
            $this->file,
            'offer/tmp/images',
            'offer/images',
            ['jpg', 'jpeg', 'gif', 'png'],
            ['image/jpeg', 'image/gif', 'image/png'],
            $this->fileNameLookup
        );
    }
}
