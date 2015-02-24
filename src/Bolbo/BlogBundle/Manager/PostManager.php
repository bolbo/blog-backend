<?php
/**
 *
 */
namespace Bolbo\BlogBundle\Manager;

use Symfony\Component\Security\Core\Util\SecureRandomInterface;

/**
 * Class PostManager
 * @package Bolbo\BlogBundle\Manager
 */
class PostManager
{
    /** @var array posts */
    protected $data = array();

    /**
     * @var \Symfony\Component\Security\Core\Util\SecureRandomInterface
     */
    protected $randomGenerator;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @param SecureRandomInterface $randomGenerator
     * @param string $cacheDir
     */
    public function __construct(SecureRandomInterface $randomGenerator, $cacheDir)
    {
        if (file_exists($cacheDir . '/sf_post_data')) {
            $data = file_get_contents($cacheDir . '/sf_post_data');
            $this->data = unserialize($data);
        }

        $this->randomGenerator = $randomGenerator;
        $this->cacheDir = $cacheDir;
    }

    /**
     *
     */
    private function flush()
    {
        file_put_contents($this->cacheDir . '/sf_post_data', serialize($this->data));
    }

    /**
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function fetch($start = 0, $limit = 5)
    {
        return array_slice($this->data, $start, $limit, true);
    }

    /**
     * @param $id
     * @return bool
     */
    public function get($id)
    {
        if (!isset($this->data[$id])) {
            return false;
        }

        return $this->data[$id];
    }

    /**
     * @param $post
     */
    public function set($post)
    {
        if (null === $post->id) {
            if (empty($this->data)) {
                $post->id = 0;
            } else {
                end($this->data);
                $post->id = key($this->data) + 1;
            }
        }

        if (null === $post->secret) {
            $post->secret = base64_encode($this->randomGenerator->nextBytes(64));
        }

        $this->data[$post->id] = $post;
        $this->flush();
    }

    /**
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        if (!isset($this->data[$id])) {
            return false;
        }

        unset($this->data[$id]);
        $this->flush();

        return true;
    }
}
