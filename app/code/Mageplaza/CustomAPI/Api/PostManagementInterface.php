<?php

namespace Mageplaza\CustomAPI\Api;

interface PostManagementInterface {
    
    /**
     * GET for post api
     * @param string $param
     * @return string
     */
    public function getPost ($param);
}