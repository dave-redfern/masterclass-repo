<?php

$di = new Aura\Di\Container(new Aura\Di\Factory());
//$di->setAutoResolve(false);

/*
 * Config loading
 */
$di->set('config_loader', $di->lazyNew(App\Services\Config\ConfigLoader::class));
$di->set('config', $di->lazy(function () use ($di) {
    return $di->get('config_loader')->load();
}));

/*
 * Utilities
 */
$di->set('method_resolver', $di->lazyNew(App\Support\MethodTypeHintResolver::class, [$di]));
$di->set(App\Services\Factory\EntityFactory::class, $di->lazyNew(App\Services\Factory\EntityFactory::class, [
    $di->lazyGet(App\Services\Auth\Authenticator::class),
]));
$di->set('request', $di->lazy(function () {
    $request = App\Support\Http\Request::newFromGlobals();
    $request->setSession(new App\Support\Http\Session());

    return $request;
}));
$di->set(App\Support\Http\Request::class, $di->lazyGet('request'));

/*
 * Db Connection
 */
$di->params[App\Services\DB\Connection::class] = [
    'config' => $di->lazyGet('config'),
];
$di->set('connection', $di->lazyNew(App\Services\DB\Connection::class));

/*
 * Router
 */
$di->params[App\Services\Router\Router::class] = [
    'config' => $di->lazyGet('config'),
    'resolver' => $di->lazyGet('method_resolver'),
];
$di->set('router', $di->lazyNew(App\Services\Router\Router::class));

/*
 * Persisters
 */
$di->params[App\Services\Persister\EntityPersister::class] = [
    'connection' => $di->lazyGet('connection'),
    'config'     => $di->lazyGet('config'),
];
$di->params[App\Persisters\CommentPersister::class] = [
    'entity' => App\Entities\Comment::class,
];
$di->params[App\Persisters\StoryPersister::class] = [
    'entity' => App\Entities\Story::class,
];
$di->params[App\Persisters\UserPersister::class] = [
    'entity' => App\Entities\User::class,
];
$di->set(App\Persisters\CommentPersister::class, $di->lazyNew(App\Persisters\CommentPersister::class));
$di->set(App\Persisters\StoryPersister::class,   $di->lazyNew(App\Persisters\StoryPersister::class));
$di->set(App\Persisters\UserPersister::class,    $di->lazyNew(App\Persisters\UserPersister::class));

/*
 * Repos
 */
$di->params[App\Services\Repository\EntityRepository::class] = [
    'connection' => $di->lazyGet('connection'),
    'config'     => $di->lazyGet('config'),
];
$di->params[App\Repositories\CommentRepository::class]        = [
    'persister' => $di->lazyGet(App\Persisters\CommentPersister::class),
    'entity'    => App\Entities\Comment::class,
];
$di->params[App\Repositories\StoryRepository::class]          = [
    'persister' => $di->lazyGet(App\Persisters\StoryPersister::class),
    'entity'    => App\Entities\Story::class,
];
$di->params[App\Repositories\UserRepository::class]           = [
    'persister' => $di->lazyGet(App\Persisters\UserPersister::class),
    'entity'    => App\Entities\User::class,
];
$di->set(App\Repositories\CommentRepository::class, $di->lazyNew(App\Repositories\CommentRepository::class));
$di->set(App\Repositories\StoryRepository::class,   $di->lazyNew(App\Repositories\StoryRepository::class));
$di->set(App\Repositories\UserRepository::class,    $di->lazyNew(App\Repositories\UserRepository::class));

/*
 * Auth
 */
$di->set(App\Services\Auth\Authenticator::class, $di->lazyNew(App\Services\Auth\Authenticator::class, [
    $di->lazyGet(App\Repositories\UserRepository::class),
    $di->lazyGet('request'),
]));

/*
 * Controllers
 */
$di->params[App\Controllers\CommentController::class] = [
    'auth'     => $di->lazyGet(App\Services\Auth\Authenticator::class),
    'stories'  => $di->lazyGet(App\Repositories\StoryRepository::class),
    'comments' => $di->lazyGet(App\Repositories\CommentRepository::class),
];
$di->params[App\Controllers\IndexController::class] = [
    'stories'  => $di->lazyGet(App\Repositories\StoryRepository::class),
    'comments' => $di->lazyGet(App\Repositories\CommentRepository::class),
];
$di->params[App\Controllers\StoryController::class] = [
    'auth'     => $di->lazyGet(App\Services\Auth\Authenticator::class),
    'stories'  => $di->lazyGet(App\Repositories\StoryRepository::class),
    'comments' => $di->lazyGet(App\Repositories\CommentRepository::class),
];
$di->params[App\Controllers\UserController::class] = [
    'auth'   => $di->lazyGet(App\Services\Auth\Authenticator::class),
    'users'  => $di->lazyGet(App\Repositories\UserRepository::class),
];
