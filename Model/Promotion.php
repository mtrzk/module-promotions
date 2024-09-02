<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Mtrzk\Promotions\Api\Data\PromotionGroupInterface;
use Mtrzk\Promotions\Api\Data\PromotionInterface;
use Mtrzk\Promotions\Api\PromotionGroupRepositoryInterface;
use Mtrzk\Promotions\Model\ResourceModel\Promotion as ResourceModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;


/**
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Promotion extends AbstractModel implements PromotionInterface
{
    protected array $groups = [];

    protected $_eventPrefix = 'mtrzk_promotions_promotion';

    protected $_eventObject = 'promotion';

    /**
     * @param Context                           $context
     * @param Registry                          $registry
     * @param PromotionGroupRepositoryInterface $promotionGroupRepository
     * @param SearchCriteriaBuilder             $searchCriteriaBuilder
     * @param AbstractResource|null             $resource
     * @param AbstractDb|null                   $resourceCollection
     * @param array                             $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        private readonly PromotionGroupRepositoryInterface $promotionGroupRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    public function getCreatedAt(): ?string
    {
        return (string)$this->getData(self::CREATED_AT);
    }

    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function getGroups(): array
    {
        $groupIds = $this->getGroupIds();

        if (empty($groupIds)) {
            return [];
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(PromotionGroupInterface::ENTITY_ID, $this->getGroupIds(), 'in');

        $searchGroups = $this->promotionGroupRepository->getList($searchCriteria->create());

        if ($searchGroups->getTotalCount() > 0) {
            return $searchGroups->getItems();
        }

        return [];
    }

    public function getGroupIds(): ?array
    {
        return $this->getData(self::GROUP_IDS);
    }

    public function setGroupIds(array $promotionGroupIds): PromotionInterface
    {
        return $this->setData(self::GROUP_IDS, $promotionGroupIds);
    }

    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    public function setName(string $name): PromotionInterface
    {
        return $this->setData(self::NAME, $name);
    }
}
