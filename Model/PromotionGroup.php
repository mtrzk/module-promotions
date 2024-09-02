<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Model;

use Mtrzk\Promotions\Api\Data\PromotionGroupInterface;
use Mtrzk\Promotions\Model\ResourceModel\Promotion as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class PromotionGroup extends AbstractModel implements PromotionGroupInterface
{
    protected $_eventPrefix = 'mtrzk_promotions_promotion_group';

    protected $_eventObject = 'group';

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

    public function setId($entityId): PromotionGroupInterface
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    public function setName(string $name): PromotionGroupInterface
    {
        return $this->setData(self::NAME, $name);
    }

    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }
}
