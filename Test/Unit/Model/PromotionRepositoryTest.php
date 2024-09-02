<?php
declare(strict_types=1);

namespace Mtrzk\Promotions\Test\Unit\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Mtrzk\Promotions\Api\Data\PromotionSearchResultInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Mtrzk\Promotions\Model\Promotion;
use PHPUnit\Framework\TestCase;
use Mtrzk\Promotions\Api\Data\PromotionInterface;
use Mtrzk\Promotions\Api\Data\PromotionInterfaceFactory;
use Mtrzk\Promotions\Model\ResourceModel\Promotion\CollectionFactory as PromotionCollectionFactory;
use Mtrzk\Promotions\Model\PromotionRepository;
use Mtrzk\Promotions\Model\ResourceModel\Promotion as PromotionResource;

class PromotionRepositoryTest extends TestCase
{
    /**
     * @var PromotionRepository
     */
    private $promotionRepository;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $promotionResourceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $promotionFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $searchResultsFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $promotionMock;

    protected function setUp(): void
    {
        $this->promotionResourceMock = $this->createMock(PromotionResource::class);
        $this->promotionFactoryMock = $this->createMock(PromotionInterfaceFactory::class);
        $this->promotionMock = $this->createPartialMock(Promotion::class, ['getId', 'addData']);
        $this->searchResultsFactoryMock = $this->createMock(PromotionSearchResultInterfaceFactory::class);

        $this->promotionRepository = new PromotionRepository(
            $this->promotionResourceMock,
            $this->searchResultsFactoryMock,
            $this->promotionFactoryMock,
            $this->createMock(PromotionCollectionFactory::class),
            $this->createMock(CollectionProcessorInterface::class),
            $this->createMock(SearchCriteriaBuilder::class)
        );
    }

    public function testSave()
    {
        $this->promotionFactoryMock->method('create')->willReturn($this->promotionMock);
        $this->promotionMock->method('getId')->willReturn(null);
        $this->promotionResourceMock->expects($this->once())
            ->method('save')
            ->with($this->promotionMock);

        $this->promotionRepository->save($this->promotionMock);
    }

    public function testGetById()
    {
        $promotionId = 1;

        $this->promotionFactoryMock->method('create')->willReturn($this->promotionMock);
        $this->promotionResourceMock->method('load')
            ->with($this->promotionMock, $promotionId)
            ->willReturn($this->promotionMock);

        $this->promotionMock->method('getId')->willReturn($promotionId);

        $result = $this->promotionRepository->getById($promotionId);

        $this->assertEquals($this->promotionMock, $result);
    }

    public function testGetByIdThrowsExceptionWhenPromotionNotFound()
    {
        $this->expectException(NoSuchEntityException::class);

        $promotionId = 1;

        $this->promotionFactoryMock->method('create')->willReturn($this->promotionMock);
        $this->promotionResourceMock->method('load')
            ->with($this->promotionMock, $promotionId)
            ->will($this->throwException(new NoSuchEntityException(__('Promotion with id "%1" does not exist.', $promotionId))));

        $this->promotionRepository->getById($promotionId);
    }

    public function testDeleteById()
    {
        $promotionId = 1;

        $this->promotionFactoryMock->method('create')->willReturn($this->promotionMock);
        $this->promotionResourceMock->method('load')
            ->with($this->promotionMock, $promotionId)
            ->willReturn($this->promotionMock);

        $this->promotionMock->method('getId')->willReturn($promotionId);

        $this->promotionResourceMock->expects($this->once())
            ->method('delete')
            ->with($this->promotionMock);

        $this->promotionRepository->deleteById($promotionId);
    }
}
