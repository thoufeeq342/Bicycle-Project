<?php

namespace Mageplaza\CustomAPI\Model;

class PostManagement {
	/**
     * {@inheritdoc}
     */
	public function getPost ($param)
	{
		return 'api GET return the $param' . $param;
	}
}