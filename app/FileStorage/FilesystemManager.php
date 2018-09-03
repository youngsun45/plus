<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\FileStorage;

use OSS\OssClient;
use Zhiyi\Plus\AppInterface;
use Illuminate\Support\Manager;
use function Zhiyi\Plus\setting;

class FilesystemManager extends Manager
{
    /**
     * Create the filesystem manager instance.
     * @param \Zhiyi\Plus\AppInterface $app
     */
    public function __construct(AppInterface $app)
    {
        parent::__construct($app);
    }

    /**
     * Get the default driver name.
     */
    public function getDefaultDriver()
    {
        return setting('core', 'file:default-filesystem', 'AliyunOSS');
    }

    /**
     * Create local driver.
     * @return \Zhiyi\Plus\FileStorage\Filesystems\FilesystemInterface
     */
    public function createLocalDriver(): Filesystems\FilesystemInterface
    {
        $filesystem = $this
            ->app
            ->make(\Illuminate\Contracts\Filesystem\Factory::class)
            ->disk(setting('core', 'file:local-filesystem-select', 'local'));

        return new Filesystems\LocalFilesystem($filesystem);
    }

    public function createAliyunOSSDriver(): Filesystems\FilesystemInterface
    {
        $bucket = setting('core', 'file:aliyun-oss-bucket', 'plus-test');
        $oss = new OssClient('LTAIXfwTSAa2RTVn', 'rqij4pL7qv0ZeC5p5dUQBOQGvCaji6', 'http://ximage.zhibocloud.cn', true);

        return new Filesystems\AliyunOSS(/* $this->app->make(\OSS\OssClient::class) */ $oss, $bucket);
    }
}