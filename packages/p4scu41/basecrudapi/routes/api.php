<?php

$NAMESPACE = 'p4scu41\BaseCRUDApi';

Route::get('basecrudapi/test', function () use ($NAMESPACE)
{
    return 'It works!' . PHP_EOL .
        'NAMESPACE: '. $NAMESPACE .
        ' Api'
        ;
});

Route::get('basecrudapi/index', 'BaseApiController@index');
