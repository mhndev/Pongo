### PHP MongoDB 
this packages contains sample source code for how to work with mongodb in php,
without help of any library, just use mongodb/mongodb official package
using repository pattern.


you should create your entities and repositories like following source codes.


#### Post Entity class
```php
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


```

consider all entities that are going to be persisted in mongo should implement iMongoEntity interface
and this method contains nothing more than hydrate object to an array which can be persisted to mongodb.


and here is .

#### Post Repository

```php

<?php
namespace mhndev\Pongo\Repository;

use mhndev\Pongo\Entity\EntityPost;
use mhndev\Pongo\Exception\exEntityNotFound;
use mhndev\Pongo\MongoHelper;
use MongoDB\BSON\ObjectId;
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


```

and here you can checkout the sample usage :


```php

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once "vendor/autoload.php";


$settings =  [
    'driver' => [
        // Master Connection Client
        'master' => [
            'host' => 'mongodb://localhost:27017',
            'options_uri' => [
            ],
            'options' => [
            ],
        ],
    ],
];

$mongoDriver = new \mhndev\Pongo\MongoDriverManager();
$mongoDriver->addClient(new MongoDB\Client($settings['driver']['master']['host'] ), 'master' );
$mongoClient = $mongoDriver->byClient('master');
$db = $mongoClient->selectDatabase('db_name');


$postRepo = new \mhndev\Pongo\Repository\RepositoryPost($db, 'posts');

$post = (new \mhndev\Pongo\Entity\EntityPost())
    ->setTitle('My New Post Title')
    ->setBody('My New Post Body')
    ->setCreatedAt(new DateTime());

$persistedPost = $postRepo->persist($post);
var_dump($persistedPost);
die();


$postEntity = $postRepo->findById('5bcae585cf2a2e1d122a2ab3');
var_dump($postEntity);
die();


$last10Posts = $postRepo->list10NewPosts();

var_dump($last10Posts);
die();


```