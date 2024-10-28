<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Ui\Component;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Exception\LocalizedException;

class CategoryOptions implements OptionSourceInterface
{
    /**
     * @var Collection
     */
    protected Collection $collectionCategory;

    /**
     * @param Collection $collectionCategory
     */
    public function __construct(Collection $collectionCategory)
    {
        $this->collectionCategory = $collectionCategory;
    }

    /**
     * Generates an array of options based on the collection category.
     *
     * This method retrieves category data, formats each entry into an associative array
     * with 'value' and 'label' keys, and returns the final array.
     *
     * @return array<int, array{value: int, label: string}> The formatted array of options.
     * @throws LocalizedException
     */
    public function toOptionArray(): array
    {
        $this->collectionCategory->addAttributeToSelect('name');

        $options = [];
        foreach ($this->collectionCategory as $category) {
            $options[] = [
                'value' => $category->getId(),
                'label' => $category->getName(),
            ];
        }

        return $options;
    }
}
