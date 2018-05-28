<?php

$NAMESPACE = 'p4scu41\BaseCRUDApi';

Route::get('basecrudapi/test', function () use ($NAMESPACE)
{
    return 'It works!' . PHP_EOL .
        'NAMESPACE: '. $NAMESPACE .
        ' Web'
        ;
});

Route::get('basecrudapi/index', 'BaseController@index');
