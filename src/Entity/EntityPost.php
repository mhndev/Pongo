<?php
namespace mhndev\Pongo\Entity;

use mhndev\Pongo\Contract\iMongoEntity;

/**
 * Class EntityPost
 * @package mhndev\Pongo\Entity
 */
class EntityPost implements iMongoEntity
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var array
     */
    protected $lastThreeComment;


    /**
     * @var \DateTime
     */
    protected $created_at;


    /**
     * @param string $id
     * @return EntityPost
     */
    public function setId(string $id): EntityPost
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return EntityPost
     */
    public function setTitle(string $title): EntityPost
    {
        $this->title = $title;

        return $this;
    }



    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $body
     * @return EntityPost
     */
    public function setBody(string $body): EntityPost
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param array $lastThreeComment
     * @return EntityPost
     */
    public function setLastThreeComment(array $lastThreeComment): EntityPost
    {
        $this->lastThreeComment = $lastThreeComment;

        return $this;
    }

    /**
     * @return array
     */
    public function getLastThreeComment(): array
    {
        return $this->lastThreeComment;
    }

    /**
     * @param \DateTime $created_at
     * @return EntityPost
     */
    public function setCreatedAt(\DateTime $created_at): EntityPost
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }


    /**
     * @return array
     */
    function toMongo()
    {
        return [
            'title' => $this->title,
            'body'  => $this->body,
            'created_at' => $this->created_at
        ];

    }

}
