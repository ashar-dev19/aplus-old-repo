<?php

namespace common;

use Yii;

class MyApplication extends \yii\web\Application
{
    public function handleRequest($request)
    {
        // Check if connection is secure
        if (!$request->isSecureConnection) {
            // Otherwise redirect to the same URL with HTTPS
            $secureUrl = str_replace('http', 'https', $request->absoluteUrl);
            // Use 301 for a permanent redirect
            return Yii::$app->getResponse()->redirect($secureUrl, 301);
        } else {
            // If secure connection, call parent implementation
            return parent::handleRequest($request);
        }
    }
}

?>
