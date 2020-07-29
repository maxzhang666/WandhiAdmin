<?php

namespace app\common\store\driver;


use app\common\store\driver\alioss\Oss;
use app\common\store\FileBase;
use app\common\store\trigger\SaveDb;

/**
 * 阿里云上传
 * Class Alioss
 * @package EasyAdmin\upload\driver
 */
class Alioss extends FileBase
{

    /**
     * 重写上传方法
     * @return array|void
     */
    public function save()
    {
        parent::save();
        $upload = Oss::instance($this->uploadConfig)
            ->save($this->completeFilePath, $this->completeFilePath);
        if ($upload['save'] == true) {
            SaveDb::trigger($this->tableName, [
                'upload_type'   => $this->uploadType,
                'original_name' => $this->file->getOriginalName(),
                'mime_type'     => $this->file->getOriginalMime(),
                'file_ext'      => strtolower($this->file->getOriginalExtension()),
                'url'           => $upload['url'],
                'create_time'   => time(),
            ]);
        }
        $this->rmLocalSave();
        return $upload;
    }

}