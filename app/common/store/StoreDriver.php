<?php


namespace app\common\store;

/**
 * 存储驱动
 * Interface StoreDriver
 * @package app\common\store
 */
interface StoreDriver
{
    public function save($objectName, $filePath);
}