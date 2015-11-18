<?php namespace ry_mid\QiniuStorage;

use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use ry_mid\QiniuStorage\Plugins\DownloadUrl;
use ry_mid\QiniuStorage\Plugins\ImageExif;
use ry_mid\QiniuStorage\Plugins\ImageInfo;
use ry_mid\QiniuStorage\Plugins\ImagePreviewUrl;
use ry_mid\QiniuStorage\Plugins\PersistentFop;
use ry_mid\QiniuStorage\Plugins\PersistentStatus;
use ry_mid\QiniuStorage\Plugins\PrivateDownloadUrl;
use ry_mid\QiniuStorage\Plugins\UploadToken;
use ry_mid\QiniuStorage\Plugins\Fetch;
use ry_mid\QiniuStorage\Plugins\PutFile;

/**
 * Class QiniuFilesystemServiceProvider
 * @package ry_mid\QiniuStorage
 */
class QiniuFilesystemServiceProvider extends ServiceProvider
{

    public function boot()
    {
        \Storage::extend(
            'qiniu',
            function ($app, $config) {
                $qiniu_adapter = new QiniuAdapter(
                    $config['access_key'],
                    $config['secret_key'],
                    $config['bucket'],
                    $config['domain']
                );
                $file_system = new Filesystem($qiniu_adapter);
                $file_system->addPlugin(new PrivateDownloadUrl());
                $file_system->addPlugin(new DownloadUrl());
                $file_system->addPlugin(new ImageInfo());
                $file_system->addPlugin(new ImageExif());
                $file_system->addPlugin(new ImagePreviewUrl());
                $file_system->addPlugin(new PersistentFop());
                $file_system->addPlugin(new PersistentStatus());
                $file_system->addPlugin(new UploadToken());
                $file_system->addPlugin(new Fetch());
                $file_system->addPlugin(new PutFile());

                return $file_system;
            }
        );
    }

    public function register()
    {
        //
    }
}
