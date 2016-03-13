<?php

$di = new Aura\Di\Container(new Aura\Di\Factory());

/*
 * Config loading
 */
$di->set('config_loader', $di->lazyNew(App\Services\Config\ConfigLoader::class));
$di->set('config', $di->lazy(function () use ($di) {
    return $di->get('config_loader')->load();
}));

/*
 * Db Connection
 */
$di->params[App\Support\Connection::class] = [
    'config' => $di->lazyGet('config'),
];
$di->set('connection', $di->lazyNew(App\Support\Connection::class));

/*
 * Router
 */
$di->params[App\Services\Router::class]['config'] = $di->lazyGet('config');
$di->set('router', $di->lazyNew(App\Services\Router::class));

/*
 * Repos
 */
$di->params[App\Repositories\EntityRepository::class] = [
    'connection' => $di->lazyGet('connection'),
    'config' => $di->lazyGet('config'),
];
$di->params[App\Repositories\CommentRepository::class] = [
    'entity' => App\Entities\Comment::class,
];
$di->params[App\Repositories\StoryRepository::class] = [
    'entity' => App\Entities\Story::class,
];
$di->params[App\Repositories\UserRepository::class] = [
    'entity' => App\Entities\User::class,
];
$di->set(App\Repositories\CommentRepository::class, $di->lazyNew(App\Repositories\CommentRepository::class));
$di->set(App\Repositories\StoryRepository::class,   $di->lazyNew(App\Repositories\StoryRepository::class));
$di->set(App\Repositories\UserRepository::class,    $di->lazyNew(App\Repositories\UserRepository::class));


/*
 * Controllers
 */
$di->params[\App\Controllers\IndexController::class] = [
    'stories'  => $di->lazyGet(App\Repositories\StoryRepository::class),
    'comments' => $di->lazyGet(App\Repositories\CommentRepository::class),
];
$di->params[\App\Controllers\UserController::class] = [
    'users'  => $di->lazyGet(App\Repositories\UserRepository::class),
];
