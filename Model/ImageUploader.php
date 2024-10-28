<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Name;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Catalog image uploader
 */
class ImageUploader
{
    /**
     * @var Database
     */
    protected Database $coreFileStorageDatabase;

    /**
     * @var WriteInterface
     */
    protected WriteInterface $mediaDirectory;
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;
    /**
     * @var string
     */
    protected string $baseTmpPath;
    /**
     * @var string
     */
    protected string $basePath;
    /**
     * @var string[]
     */
    protected array $allowedExtensions;
    /**
     * @var UploaderFactory
     */
    private UploaderFactory $uploaderFactory;
    /**
     * @var string[]
     */
    private array $allowedMimeTypes;

    /**
     * @var Name
     */
    private mixed $fileNameLookup;

    /**
     * @var File
     */
    private File $file;

    /**
     * ImageUploader constructor.
     *
     * @param Database              $coreFileStorageDatabase
     * @param Filesystem            $filesystem
     * @param UploaderFactory       $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface       $logger
     * @param File                  $file
     * @param string                $baseTmpPath
     * @param string                $basePath
     * @param string[]              $allowedExtensions
     * @param string[]              $allowedMimeTypes
     * @param Name|null             $fileNameLookup
     *
     * @throws FileSystemException
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Database              $coreFileStorageDatabase,
        Filesystem            $filesystem,
        UploaderFactory       $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface       $logger,
        File                  $file,
        string                $baseTmpPath,
        string                $basePath,
        array                 $allowedExtensions,
        array                 $allowedMimeTypes = [],
        Name                  $fileNameLookup = null
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(
            DirectoryList::MEDIA
        );
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->file = $file;
        $this->baseTmpPath = $baseTmpPath;
        $this->basePath = $basePath;
        $this->allowedExtensions = $allowedExtensions;
        $this->allowedMimeTypes = $allowedMimeTypes;
        $this->fileNameLookup = $fileNameLookup ?? ObjectManager::getInstance()->get(
            Name::class
        );
    }

    /**
     * Checking file for moving and move it
     *
     * @param string $imageName
     * @param bool   $returnRelativePath
     *
     * @return void
     *
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function moveFileFromTmp(
        string $imageName,
        bool   $returnRelativePath = false
    ): void {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();

        $baseImagePath = $this->getFilePath(
            $basePath,
            $this->fileNameLookup->getNewFileName(
                $this->mediaDirectory->getAbsolutePath(
                    $this->getFilePath($basePath, $imageName)
                )
            )
        );

        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);
        try {
            $this->coreFileStorageDatabase->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (Exception $e) {
            $this->logger->critical($e);
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).'),
                $e
            );
        }
    }

    /**
     * Retrieve base tmp path
     *
     * @return string
     */
    public function getBaseTmpPath(): string
    {
        return $this->baseTmpPath;
    }

    /**
     * Set base tmp path
     *
     * @param string $baseTmpPath
     *
     * @return void
     */
    public function setBaseTmpPath(string $baseTmpPath): void
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * Retrieve base path
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Set base path
     *
     * @param string $basePath
     *
     * @return void
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath(string $path, string $imageName): string
    {
        $path = rtrim($path, '/');
        $imageName = ltrim($imageName, '/');

        return $path . '/' . $imageName;
    }

    /**
     * Checking file for save and save it to tmp dir
     *
     * @param string $fileId
     *
     * @return string[]
     *
     * @throws LocalizedException
     * @throws Exception
     */
    public function saveFileToTmpDir(string $fileId): array
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);

        if (!$uploader->checkMimeType($this->allowedMimeTypes)) {
            throw new LocalizedException(__('File validation failed.'));
        }
        $result = $uploader->save(
            $this->mediaDirectory->getAbsolutePath($baseTmpPath)
        );
        if (!$result) {
            throw new LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }
        unset($result['path']);

        $result['tmp_name'] = isset($result['tmp_name']) ? str_replace(
            '\\',
            '/',
            $result['tmp_name']
        ) : '';
        /** @phpstan-ignore-next-line */
        $result['url'] = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];
        if (isset($result['file'])) {
            try {
                $relativePath = rtrim(
                    $baseTmpPath,
                    '/'
                ) . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __(
                        'Something went wrong while saving the file(s): ' . $e->getMessage(),
                        $e
                    ),
                    $e
                );
            }
        }

        return $result;
    }

    /**
     * Retrieve allowed extensions
     *
     * @return string[]
     */
    public function getAllowedExtensions(): array
    {
        return $this->allowedExtensions;
    }

    /**
     * Set allowed extensions
     *
     * @param string[] $allowedExtensions
     *
     * @return void
     */
    public function setAllowedExtensions(array $allowedExtensions): void
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Check if the image is movable.
     *
     * @param string $imageName Name of the image to check
     *
     * @return bool True if the image is movable, false otherwise
     * @throws FileSystemException
     */
    public function movable(string $imageName): bool
    {
        $baseTmpPath = $this->getBaseTmpPath();

        $tmpImagePath = $this->mediaDirectory->getAbsolutePath(
            $this->getFilePath($baseTmpPath, $imageName)
        );

        if ($this->file->isExists($tmpImagePath)) {
            return true;
        } else {
            return false;
        }
    }
}
