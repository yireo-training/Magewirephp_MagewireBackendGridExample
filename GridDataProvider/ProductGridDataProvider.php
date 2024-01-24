<?php

declare(strict_types=1);

namespace Magewirephp\MagewireBackendGridExample\GridDataProvider;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magewirephp\MagewireBackendGrid\Grid\DataProvider\GridDataProviderInterface;
use Magewirephp\MagewireBackendGrid\Grid\State;

class ProductGridDataProvider implements GridDataProviderInterface
{
    private ?State $state = null;
    private array $items = [];
    
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }
    
    public function setState(State $state): GridDataProviderInterface
    {
        $this->state = $state;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getItems(): array
    {
        if ($this->items) {
            return $this->items;
        }
        
        $this->searchCriteriaBuilder->setCurrentPage($this->state->getPage());
        $this->searchCriteriaBuilder->setPageSize($this->state->getLimit());
        
        if (!empty($this->state->getSearch())) {
            $this->searchCriteriaBuilder->addFilter('name', '%'.$this->state->getSearch().'%', 'like');
        }
        
        $searchResults = $this->productRepository->getList($this->searchCriteriaBuilder->create());
        $this->items = $searchResults->getItems();
        $this->state->setTotalItems($searchResults->getTotalCount());
        return $this->items;
    }
    
    /**
     * @return string[]
     */
    public function getColumns(): array
    {
        return [
            'entity_id' => 'ID',
            'name' => 'Product Name',
            'sku' => 'Product SKU',
            'type' => 'Type',
            'visibility' => 'Visibility',
        ];
    }
}
