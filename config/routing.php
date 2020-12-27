<?php

$routing = array(
	'/admin\/(.*?)\/(.*?)\/(.*)/i' => 'admin/\1_\2/\3',
    '/rest\/tests(.*?)/i' => 'tests/index',
    '/^v1\/users\/login(.*?)/i' => "users/vLogin", //GET, POST
    '/^v1\/users\/edit(.*?)/i' => "users/vEdit", //GET, POST
    '/^v1\/users\/register(.*?)/i' => "users/vRegister", //GET, POST
    '/^v1\/users\/follow(.*?)/i' => "users/vFollow", //GET, POST
    '/^v1\/users\/react(.*?)/i' => "users/vReact", //GET, POST
    '/^v1\/users\/(.*)\/posts/i' => 'users/vgetPosts/\1',
    '/^v1\/users\/search(.*?)/i' => "users/vSearch", //GET, POST
    '/^v1\/users\/test(.*?)/i' => "users/vTest", //GET, POST

    '/^v1\/posts\/add(.*?)/i' => "posts/vAdd",
    '/^v1\/posts\/list(.*?)/i' => 'posts/vGetPostList',

    '/^users\/login(.*?)/i' => "users/login", //GET
    '/^users\/view_profile(.*?)/i' => "users/view_profile", //GET
    '/^users\/view(.*?)/i' => "users/view_post", //GET



    '/^users\/register(.*?)/i' => "users/register", //GET, POST
    '/^users\/edit(.*?)/i' => "users/edit", //GET, POST
    '/^users\/(.*)/i' => "users/view", //GET



    '/^posts\/add(.*?)/i' => "posts/add", // GET, POST
    '/^posts\/edit(.*?)/i' => "posts/edit", // GET, POST
    '/^posts\/view(.*?)/i' => "posts/view"
);

$default['controller'] = 'categories';
$default['action'] = 'index';