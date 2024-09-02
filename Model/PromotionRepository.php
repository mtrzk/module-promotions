<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Mtrzk\Promotions\Api\PromotionRepositoryInterface;
use Mtrzk\Promotions\Api\Data\PromotionInterface;
use Mtrzk\Promotions\Api\Data\PromotionInterfaceFactory;
use Mtrzk\Promotions\Api\Data\PromotionSearchResultInterfaceFactory;
use Mtrzk\Promotions\Model\Promotion as PromotionModel;
use Mtrzk\Promotions\Model\PromotionGroup as PromotionGroupModel;
use Mtrzk\Promotions\Model\ResourceModel\Promotion;
use Mtrzk\Promotions\Model\ResourceModel\Promotion\CollectionFactory as PromotionCollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class PromotionRepository implements PromotionRepositoryInterface
{
    private array $promotionById = [];

    /**
     * @param Promotion                             $promotionResourceModel
     * @param PromotionSearchResultInterfaceFactory $searchResultsFactory
     * @param PromotionInterfaceFactory             $promotionFactory
     * @param PromotionCollectionFactory            $promotionCollectionFactory
     * @param CollectionProcessorInterface          $collectionProcessor
     * @param SearchCriteriaBuilder                 $searchCriteriaBuilder
     */
    public function __construct(
        private readonly Promotion $promotionResourceModel,
        private readonly PromotionSearchResultInterfaceFactory $searchResultsFactory,
        private readonly PromotionInterfaceFactory $promotionFactory,
        private readonly PromotionCollectionFactory $promotionCollectionFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(PromotionInterface $entity): PromotionInterface
    {
        try {
            /** @var PromotionInterface $newPromotionData */
            $newPromotionData   = $this->promotionFactory->create();
            $newPromotionData->addData($entity->getData());

            /** @phpstan-ignore-next-line  */
            $this->promotionResourceModel->save($newPromotionData);

            if (!empty($entity->getGroupIds())) {
                $this->addGroupRelated((int)$newPromotionData->getId(), $entity->getGroupIds());
            }

            $this->promotionById[$entity->getId()] = $entity;
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        /** @phpstan-ignore-next-line  */
        return $entity;
    }

    /**
     * @inheirtDoc
     * @throws NoSuchEntityException|LocalizedException
     */
    public function getById(int $id): PromotionInterface
    {
        if (isset($this->promotionById[$id])) {
            return $this->promotionById[$id];
        }

        /** @var PromotionModel $model */
        $model = $this->promotionFactory->create();
        /** @phpstan-ignore-next-line */
        $this->promotionResourceModel->load($model, $id);

        if (!$model->getId()) {
            throw NoSuchEntityException::singleField($this->promotionResourceModel->getIdFieldName(), $id);
        }

        $this->promotionById[$id] = $model;

        return $model;
    }

    /**
     * @inheirtDoc
     */
    public function getList(?SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        }

        $collection    = $this->promotionCollectionFactory->create();
        $searchResults = $this->searchResultsFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setSearchCriteria($searchCriteria);

        /** @phpstan-ignore-next-line */
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param int $id
     *
     * @return void
     *
     * @throws NoSuchEntityException|LocalizedException
     */
    public function deleteById(int $id): void
    {
        /** @var PromotionGroupModel $model */
        $model = $this->getById($id);

        $this->promotionResourceModel->delete($model);
    }

    private function addGroupRelated(int $entityId, array $newGroupsIds): void
    {
        $connection = $this->promotionResourceModel->getConnection();
        $oldGroups = $this->promotionResourceModel->lookupGroupIds($entityId);
        $table = $this->promotionResourceModel->getTable(Promotion::RELATED_TABLE);
        $delete = array_diff($oldGroups, $newGroupsIds);
        $insert = array_diff($newGroupsIds, $oldGroups);

        if ($delete) {
            $where = [
                sprintf("%s = ?",  Promotion::PROMOTION_ID) => $entityId,
                sprintf("%s IN (?)", Promotion::GROUP_ID) => $delete,
            ];
            $connection->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $groupId) {
                $data[] = [
                    Promotion::PROMOTION_ID => $entityId,
                    Promotion::GROUP_ID => (int)$groupId,
                ];
            }

            $connection->insertMultiple($table, $data);
        }
    }
}
