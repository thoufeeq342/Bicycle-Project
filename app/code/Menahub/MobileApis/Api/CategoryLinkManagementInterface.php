<?php 
namespace Menahub\MobileApis\Api;
 
/**
 * @api
 */
interface CategoryLinkManagementInterface
{
     /**
       * GET for getProducts API
       * 
       * @param int $pageSize
       * @param int $currentPage
       * @return array
       */
      public function getProducts($pageSize = 10, $currentPage = 1);
}
