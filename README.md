# Mtrzk_Promotions

## Overview

Mtrzk_Promotions is a Magento 2 module designed to manage promotions and promotion groups within the Magento platform. 
This module allows you to easily create, retrieve, update, and delete promotions and promotion groups through a REST API.

## Installation

You can install the module using Composer. Run the following command in your Magento 2 root directory:

```bash
composer require mtrzk/module-promotions
```

After installation, enable the module and update the database schema by running:

```bash
bin/magento module:enable Mtrzk_Promotions
bin/magento setup:upgrade
```

## Testing

To run the unit tests for the module, use the following commands:

```bash
vendor/bin/phpunit -c dev/tests/unit/phpunit.xml app/code/Mtrzk/Promotions/Test/Unit/Model/PromotionGroupRepositoryTest.php
vendor/bin/phpunit -c dev/tests/unit/phpunit.xml app/code/Mtrzk/Promotions/Test/Unit/Model/PromotionRepositoryTest.php
```

## API Endpoints

The Mtrzk_Promotions module exposes several REST API endpoints for managing promotions and promotion groups:

### Promotions API

- **GET /V1/promotions**
  - Retrieves a list of promotions.
  - ACL: `Mtrzk_Promotions::promotions_view`

- **GET /V1/promotions/:id**
  - Retrieves a specific promotion by ID.
  - ACL: `Mtrzk_Promotions::promotions_view`

- **POST /V1/promotions**
  - Creates or updates a promotion.
  - ACL: `Mtrzk_Promotions::promotions_save`

- **DELETE /V1/promotions/:id**
  - Deletes a specific promotion by ID.
  - ACL: `Mtrzk_Promotions::promotions_delete`

### Promotion Groups API

- **GET /V1/promotion-groups**
  - Retrieves a list of promotion groups.
  - ACL: `Mtrzk_Promotions::promotion_groups_view`

- **GET /V1/promotion-groups/:id**
  - Retrieves a specific promotion group by ID.
  - ACL: `Mtrzk_Promotions::promotion_groups_view`

- **POST /V1/promotion-groups**
  - Creates or updates a promotion group.
  - ACL: `Mtrzk_Promotions::promotion_groups_save`

- **DELETE /V1/promotion-groups/:id**
  - Deletes a specific promotion group by ID.
  - ACL: `Mtrzk_Promotions::promotion_groups_delete`
