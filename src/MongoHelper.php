<?php
namespace mhndev\Pongo;

use mhndev\Pongo\Entity\EntityPost;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Model\BSONDocument;

/**
 * Class MongoHelper
 * @package mhndev\Pongo
 */
class MongoHelper
{

    /**
     * @param \DateTime $date
     * @return UTCDateTime
     */
    static function DateTimeToUtc(\DateTime $date)
    {
        return new UTCDateTime($date->getTimestamp() * 1000);
    }


    /**
     * @param UTCDateTime $UTCDateTime
     * @return \DateTime
     */
    static function UtcToDateTime(UTCDateTime $UTCDateTime)
    {
        return $UTCDateTime->toDateTime();
    }

    /**
     * @param EntityPost $post
     * @return array
     */
    static function postEntityToMongoPersistable(EntityPost $post)
    {
        $insertArray = $post->toMongo();

        /** @var \DateTime $dateTime */
        $dateTime = $insertArray['created_at'];
        $insertArray['created_at'] = self::DateTimeToUtc($dateTime);

        return $insertArray;
    }


    /**
     * @param array $post
     * @return EntityPost
     */
    static function postMongoToEntityPost(array $post)
    {
        return (new EntityPost())
            ->setId($post['_id'])
            ->setTitle($post['title'])
            ->setBody($post['body'])
            ->setCreatedAt(self::UtcToDateTime($post['created_at']));
    }


    /**
     * @param array $posts
     * @return array
     */
    static function arrayPostMongoToEntity(array $posts)
    {
        $result = [];

        /** @var BSONDocument $post */
        foreach ($posts as $post) {
            $result[] = self::postMongoToEntityPost(iterator_to_array($post));
        }

        return $result;
    }



}
