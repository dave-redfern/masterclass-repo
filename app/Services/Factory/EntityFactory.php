<?php

namespace App\Services\Factory;

use App\Contracts\Entity\Blamable as BlamableContract;
use App\Entities\Comment;
use App\Entities\Story;
use App\Entities\User;
use App\Services\Auth\Authenticator;

/**
 * Class EntityFactory
 *
 * @package    App\Services\Factory
 * @subpackage App\Services\Factory\EntityFactory
 */
class EntityFactory
{

    /**
     * @var Authenticator
     */
    protected $auth;



    /**
     * Constructor.
     *
     * @param Authenticator $auth
     */
    public function __construct(Authenticator $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Story|null  $story
     * @param null|string $comment
     *
     * @return Comment
     */
    public function createComment(Story $story = null, $comment = null)
    {
        $entity = new Comment($story, $comment);

        $this->applyCreator($entity);

        return $entity;
    }

    /**
     * @param null|string $headline
     * @param null|string $url
     *
     * @return Story
     */
    public function createStory($headline = null, $url = null)
    {
        $entity = new Story($headline, $url);

        $this->applyCreator($entity);

        return $entity;
    }

    /**
     * @param null|string $username
     * @param null|string $email
     * @param null|string $password
     *
     * @return User
     */
    public function createUser($username = null, $email = null, $password = null)
    {
        $entity = new User($username, $email, password_hash($password, PASSWORD_BCRYPT));

        $this->applyCreator($entity);

        return $entity;
    }



    /**
     * Sets the creator, if a user is currently authenticated
     *
     * @param mixed $entity
     */
    protected function applyCreator($entity)
    {
        if ($entity instanceof BlamableContract && $this->auth->user()) {
            $entity->setCreatedBy($this->auth->user()->getUsername());
        }
    }
}
