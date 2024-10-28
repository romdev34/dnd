<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Test\Unit\Ui\Component;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RomainDndOffers\Offers\Ui\Component\CategoryOptions;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\Category;
use ArrayIterator;

class CategoryOptionsTest extends TestCase
{
    /**
     * @var Collection|MockObject
     */
    private Collection|MockObject $collectionCategoryMock;

    /**
     * @var CategoryOptions
     */
    private CategoryOptions $categoryOptions;

    protected function setUp(): void
    {
        $this->collectionCategoryMock = $this->createMock(Collection::class);
        $this->categoryOptions = new CategoryOptions($this->collectionCategoryMock);
    }

    public function testToOptionArray()
    {
        $categoryMock1 = $this->createMock(Category::class);
        $categoryMock2 = $this->createMock(Category::class);

        // Mock the return values for category 1
        $categoryMock1->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $categoryMock1->expects($this->once())
            ->method('getName')
            ->willReturn('Category 1');

        // Mock the return values for category 2
        $categoryMock2->expects($this->once())
            ->method('getId')
            ->willReturn(2);

        $categoryMock2->expects($this->once())
            ->method('getName')
            ->willReturn('Category 2');

        // Mock the collection
        $this->collectionCategoryMock->expects($this->once())
            ->method('addAttributeToSelect')
            ->with('name')
            ->willReturnSelf();

        $this->collectionCategoryMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new ArrayIterator([$categoryMock1, $categoryMock2]));

        // Define the expected result
        $expectedResult = [
            ['value' => 1, 'label' => 'Category 1'],
            ['value' => 2, 'label' => 'Category 2'],
        ];

        // Assert the result is as expected
        $this->assertEquals($expectedResult, $this->categoryOptions->toOptionArray());
    }
}
