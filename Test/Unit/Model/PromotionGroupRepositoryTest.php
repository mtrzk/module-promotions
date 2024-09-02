<?php
declare(strict_types=1);

namespace Mtrzk\Promotions\Test\Unit\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Mtrzk\Promotions\Api\Data\PromotionGroupSearchResultInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Mtrzk\Promotions\Model\PromotionGroup;
use PHPUnit\Framework\TestCase;
use Mtrzk\Promotions\Api\Data\PromotionGroupInterfaceFactory;
use Mtrzk\Promotions\Model\ResourceModel\PromotionGroup\CollectionFactory as PromotionGroupCollectionFactory;
use Mtrzk\Promotions\Model\PromotionGroupRepository;
use Mtrzk\Promotions\Model\ResourceModel\PromotionGroup as PromotionGroupResource;

class PromotionGroupRepositoryTest extends TestCase
{
    /**
     * @var PromotionGroupRepository
     */
    private $promotionGroupRepository;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $promotionGroupResourceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $promotionGroupFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $searchResultsFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $promotionGroupMock;

    protected function setUp(): void
    {
        $this->promotionGroupResourceMock = $this->createMock(PromotionGroupResource::class);
        $this->promotionGroupFactoryMock = $this->createMock(PromotionGroupInterfaceFactory::class);
        $this->promotionGroupMock = $this->createPartialMock(PromotionGroup::class, ['getId', 'addData']);
        $this->searchResultsFactoryMock = $this->createMock(PromotionGroupSearchResultInterfaceFactory::class);

        $this->promotionGroupRepository = new PromotionGroupRepository(
            $this->promotionGroupResourceMock,
            $this->searchResultsFactoryMock,
            $this->promotionGroupFactoryMock,
            $this->createMock(PromotionGroupCollectionFactory::class),
            $this->createMock(CollectionProcessorInterface::class),
            $this->createMock(SearchCriteriaBuilder::class)
        );
    }

    public function testSave()
    {
        $this->promotionGroupFactoryMock->method('create')->willReturn($this->promotionGroupMock);
        $this->promotionGroupMock->method('getId')->willReturn(null);
        $this->promotionGroupResourceMock->expects($this->once())
            ->method('save')
            ->with($this->promotionGroupMock);

        $this->promotionGroupRepository->save($this->promotionGroupMock);
    }

    public function testGetById()
    {
        $promotionGroupId = 1;

        $this->promotionGroupFactoryMock->method('create')->willReturn($this->promotionGroupMock);
        $this->promotionGroupResourceMock->method('load')
            ->with($this->promotionGroupMock, $promotionGroupId)
            ->willReturn($this->promotionGroupMock);

        $this->promotionGroupMock->method('getId')->willReturn($promotionGroupId);

        $result = $this->promotionGroupRepository->getById($promotionGroupId);

        $this->assertEquals($this->promotionGroupMock, $result);
    }

    public function testGetByIdThrowsExceptionWhenPromotionGroupNotFound()
    {
        $this->expectException(NoSuchEntityException::class);

        $promotionGroupId = 1;

        $this->promotionGroupFactoryMock->method('create')->willReturn($this->promotionGroupMock);
        $this->promotionGroupResourceMock->method('load')
            ->with($this->promotionGroupMock, $promotionGroupId)
            ->will($this->throwException(new NoSuchEntityException(__('PromotionGroup with id "%1" does not exist.', $promotionGroupId))));

        $this->promotionGroupRepository->getById($promotionGroupId);
    }

    public function testDeleteById()
    {
        $promotionGroupId = 1;

        $this->promotionGroupFactoryMock->method('create')->willReturn($this->promotionGroupMock);
        $this->promotionGroupResourceMock->method('load')
            ->with($this->promotionGroupMock, $promotionGroupId)
            ->willReturn($this->promotionGroupMock);

        $this->promotionGroupMock->method('getId')->willReturn($promotionGroupId);

        $this->promotionGroupResourceMock->expects($this->once())
            ->method('delete')
            ->with($this->promotionGroupMock);

        $this->promotionGroupRepository->deleteById($promotionGroupId);
    }
}
