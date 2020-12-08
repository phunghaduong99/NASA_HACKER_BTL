<?php

$routing = array(
	'/admin\/(.*?)\/(.*?)\/(.*)/' => 'admin/\1_\2/\3',
    '/rest\/tests(.*?)/' => 'tests/index'
);

$default['controller'] = 'categories';
$default['action'] = 'index';