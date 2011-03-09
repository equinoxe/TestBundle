<?php
/**
 * The code is part of the Flexiflow project.
 */

namespace Equinoxe\TestBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;
use Symfony\Component\Finder\Finder;

/**
 * Base class for unit testing.
 */
abstract class WebTestCase extends SymfonyWebTestCase implements \Serializable
{

    /**
     * Kernel of the system.
     *
     * @var \Symfony\Component\HttpKernel\Kernel
     */
    protected $kernel;

    /**
     * Creates and returns a client to simulate http-requests.
     *
     * @param array $options Options for the client.
     * @param array $server  Server for the client.
     * 
     * @return \Symfony\Bundle\FrameworkBundle\Client The client object.
     */
    public function createClient(array $options = array(), array $server = array())
    {
        $client = parent::createClient($options, $server);
        return $client;
    }

    /**
     * Creates the kernel and returns the service container.
     *
     * @param array $options Options for the kernel.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface The service container.
     */
    public function createContainer(array $options=array())
    {
        $this->kernel = $this->createKernel($options);
        $this->kernel->boot();
        return $this->kernel->getContainer();
    }

    /**
     * Serzialize this to nothing to avoid serialization of PDO instances.
     *
     * @return string An empty string.
     */
    public function serialize()
    {
        return serialize('');
    }

    /**
     * Implementation of the Serializable interface.
     *
     * @param string $data The serialized string.
     */
    public function unserialize($data)
    {
        $this->__construct();
    }

    public function generateRandomString($length = 10)
    {
        return substr(md5(uniqid()), 0, $length);
    }

    public function generateRandomNumber($min = 1, $max = 100)
    {
        return mt_rand($min, $max);
    }

    public function createEntityManagerMock()
    {
        if ($this->kernel == null) {
            $this->createContainer();
        }
        return new EntityManagerMock($this->kernel->getContainer()->get('doctrine.orm.entity_manager'));
    }
    
}
