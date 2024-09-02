<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Model\ResourceModel\Promotion;

use Mtrzk\Promotions\Model\Promotion as Model;
use Mtrzk\Promotions\Model\ResourceModel\Promotion as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    public function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
