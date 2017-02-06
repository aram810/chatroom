<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'room/index',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'imagemanager' => [
	        'class' => 'noam148\imagemanager\components\ImageManagerGetPath',
	        //set media path (outside the web folder is possible)
	        'mediaPath' => Yii::getAlias('@basePath') . DIRECTORY_SEPARATOR . 'file_resource',
	        //path relative web folder to store the cache images
	        'cachePath' => 'images',
	        //use filename (seo friendly) for resized images else use a hash
	        'useFilename' => true,
	        //show full url (for example in case of a API)
	        'absoluteUrl' => false,
        ],
    ],
    'modules' => [
	    'imagemanager' => [
		    'class' => 'noam148\imagemanager\Module',
		    'canUploadImage' => true,
		    'canRemoveImage' => function(){
			    return true;
		    },
		    'cssFiles' => [
			    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css',
		    ],
	    ],
    ],
    'params' => $params,
];
