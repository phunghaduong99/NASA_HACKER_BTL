<?php

$routing = array(
	'/admin\/(.*?)\/(.*?)\/(.*)/i' => 'admin/\1_\2/\3',
    '/rest\/tests(.*?)/i' => 'tests/index',
    '/^v1\/users\/login(.*?)/i' => "users/vLogin", //GET, POST
    '/^v1\/users\/edit(.*?)/i' => "users/vEdit", //GET, POST
    '/^v1\/users\/register(.*?)/i' => "users/vRegister", //GET, POST

    '/^users\/login(.*?)/i' => "users/login", //GET
    '/^users\/register(.*?)/i' => "users/register", //GET, POST
    '/^users\/edit(.*?)/i' => "users/edit", //GET, POST
    '/^users\/(.*)/i' => "users/view", //GET

    '/^posts\/new(.*?)/i' => "posts/new", // GET, POST
    '/^posts\/edit(.*?)/i' => "posts/edit", // GET, POST
    '/^posts\/view(.*?)/i' => "posts/view"
);

$default['controller'] = 'categories';
$default['action'] = 'index';