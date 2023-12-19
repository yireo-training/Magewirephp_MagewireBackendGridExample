<?php
declare(strict_types=1);

namespace Magewirephp\MagewireBackendGridExample\GridDataProvider;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magewirephp\MagewireBackendGrid\Grid\DataProvider\GridDataProviderInterface;

class ProductGridDataProvider implements GridDataProviderInterface
{
    private int $totalCount = 0;
    private int $totalItems = 0;
    
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }
    
    /**
     * @return array
     */
    public function getItems(int $page = 0, int $limit = 20, string $search = ''): array
    {
        $this->searchCriteriaBuilder->setCurrentPage($page);
        $this->searchCriteriaBuilder->setPageSize($limit);
        
        if (!empty($search)) {
            $this->searchCriteriaBuilder->addFilter('name', $search);
        }
        
        $searchResults = $this->productRepository->getList($this->searchCriteriaBuilder->create());
        $this->totalCount = $searchResults->getTotalCount();
        $this->totalItems = $searchResults->getTotalCount();
        return $searchResults->getItems();
    }
    
    public function getTotalPages(int $limit): int
    {
        return $this->totalCount;
    }
    
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
    
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
}
