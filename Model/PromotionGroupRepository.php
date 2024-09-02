<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Model;

use Magento\Framework\Api\SearchResultsInterface;
use Mtrzk\Promotions\Api\Data\PromotionGroupInterface;
use Mtrzk\Promotions\Api\Data\PromotionGroupInterfaceFactory;
use Mtrzk\Promotions\Api\Data\PromotionGroupSearchResultInterfaceFactory;
use Mtrzk\Promotions\Model\PromotionGroup as PromotionGroupModel;
use Mtrzk\Promotions\Model\ResourceModel\PromotionGroup;
use Mtrzk\Promotions\Model\ResourceModel\PromotionGroup\CollectionFactory as PromotionGroupCollectionFactory;
use Mtrzk\Promotions\Api\PromotionGroupRepositoryInterface;
use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PromotionGroupRepository implements PromotionGroupRepositoryInterface
{
    /** @var PromotionGroupInterface[] */
    private array $promotionGroupById = [];

    /**
     * @param PromotionGroup                             $promotionGroupResourceModel
     * @param PromotionGroupSearchResultInterfaceFactory $searchResultsFactory
     * @param PromotionGroupInterfaceFactory             $promotionGroupFactory
     * @param PromotionGroupCollectionFactory            $promotionGroupCollectionFactory
     * @param CollectionProcessorInterface               $collectionProcessor
     * @param SearchCriteriaBuilder                      $searchCriteriaBuilder
     */
    public function __construct(
        private readonly PromotionGroup $promotionGroupResourceModel,
        private readonly PromotionGroupSearchResultInterfaceFactory $searchResultsFactory,
        private readonly PromotionGroupInterfaceFactory $promotionGroupFactory,
        private readonly PromotionGroupCollectionFactory $promotionGroupCollectionFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    public function save(PromotionGroupInterface $entity): PromotionGroupInterface
    {
        try {
            /** @var PromotionGroupInterface $newPromotionGroupData */
            $newPromotionGroupData   = $this->promotionGroupFactory->create();
            $newPromotionGroupData->addData($entity->getData());

            /** @phpstan-ignore-next-line  */
            $this->promotionGroupResourceModel->save($newPromotionGroupData);
            $this->promotionGroupById[$entity->getId()] = $newPromotionGroupData;
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $newPromotionGroupData;
    }

    public function getById(int $id): PromotionGroupInterface
    {
        if (isset($this->promotionGroupById[$id])) {
            return $this->promotionGroupById[$id];
        }

        /** @var PromotionGroupModel $model */
        $model = $this->promotionGroupFactory->create();
        /** @phpstan-ignore-next-line */
        $this->promotionGroupResourceModel->load($model, $id);

        if (!$model->getId()) {
            throw NoSuchEntityException::singleField($this->promotionGroupResourceModel->getIdFieldName(), $id);
        }

        $this->promotionGroupById[$id] = $model;

        return $model;
    }

    public function deleteById(int $id): void
    {
        /** @var PromotionGroupModel $model */
        $model = $this->getById($id);

        $this->promotionGroupResourceModel->delete($model);
    }

    public function getList(?SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        }

        $collection    = $this->promotionGroupCollectionFactory->create();
        $searchResults = $this->searchResultsFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setSearchCriteria($searchCriteria);

        /** @phpstan-ignore-next-line */
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
