<?php declare(strict_types=1);

namespace Magewirephp\MagewireBackendGridExample\Controller\Adminhtml\Product;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory as ResultPageFactory;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private ResultPageFactory $resultPageFactory
    ) {}

    public function execute()
    {
        $resultPage =$this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('MageWire Products Grid'));
        return $resultPage;
    }
}
