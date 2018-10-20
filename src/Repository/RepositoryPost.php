<?php
namespace mhndev\Pongo\Repository;

use mhndev\Pongo\Entity\EntityPost;
use mhndev\Pongo\Exception\exEntityNotFound;
use mhndev\Pongo\MongoHelper;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;
use MongoDB\Database;

/**
 * Class RepositoryPost
 * @package mhndev\Pongo\Repository
 */
class RepositoryPost
{

    /**
     * @var Collection
     */
    protected $gateway;

    /**
     * RepositoryPost constructor.
     * @param Database $db
     * @param string $collectionName
     */
    function __construct(Database $db, string $collectionName)
    {
        $this->gateway = $db->selectCollection($collectionName);
    }


    /**
     * @param EntityPost $post
     * @return EntityPost
     */
    function persist(EntityPost $post)
    {
        $result = $this->gateway->insertOne(MongoHelper::postEntityToMongoPersistable($post));

        return $post->setId($result->getInsertedId());
    }


    /**
     * @param string $id
     * @return EntityPost
     * @throws exEntityNotFound
     */
    function findById(string $id)
    {
        $record = $this->gateway->findOne(['_id' => new ObjectId($id)]);

        if(is_null($record)) {
            throw new exEntityNotFound;
        }

        return MongoHelper::postMongoToEntityPost(iterator_to_array($record));

    }

    /**
     * @return array
     */
    function list10NewPosts()
    {
        $result = $this->gateway->find([], ['$sort' => ['created_at' => -1 ] ])->toArray();

        return MongoHelper::arrayPostMongoToEntity($result);
    }


}
