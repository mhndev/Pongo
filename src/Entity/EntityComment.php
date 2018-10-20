<?php
namespace mhndev\Pongo\Entity;

/**
 * Class EntityComment
 * @package mhndev\Pongo\Entity
 */
class EntityComment
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var EntityPost
     */
    protected $post = null;

    /**
     * @var \DateTime
     */
    protected $created_at;

    /**
     * @param string $id
     * @return EntityComment
     */
    public function setId(string $id): EntityComment
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
     * @param string $body
     * @return EntityComment
     */
    public function setBody(string $body): EntityComment
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
     * @param EntityPost $post
     * @return EntityComment
     */
    public function setPost(EntityPost $post): EntityComment
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return EntityPost
     */
    public function getPost(): EntityPost
    {
        return $this->post;
    }

    /**
     * @param \DateTime $created_at
     * @return EntityComment
     */
    public function setCreatedAt(\DateTime $created_at): EntityComment
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


}
