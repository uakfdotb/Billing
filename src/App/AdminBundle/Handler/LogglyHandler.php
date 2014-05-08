<?php
namespace App\AdminBundle\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LogglyHandler extends AbstractProcessingHandler implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;
    private $loaded = false;
    private $isEnabled = true;
    private $key;
    private $port = 443;
    private $host = 'logs.loggly.com';
    private $entityManager;

    public function __construct()
    {
        parent::__construct(Logger::ERROR, true);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadConfiguration()
    {
        $this->port      = $this->container->getParameter('loggly_port');
        $this->key       = $this->container->getParameter('loggly_consumer_key');
        $this->host      = $this->container->getParameter('loggly_api_url');
        $this->isEnabled = $this->container->getParameter('loggly_enabled') || $this->container->getParameter('loggly_enabled') == 'true';
        $this->loaded = true;
    }

    public function handleBatch(array $records)
    {
        if (!$this->isEnabled) {
            return false;
        }

        $messages = array();

        foreach ($records as $record) {
            if ($record['level'] < $this->level) {
                continue;
            }
            $messages[] = $this->processRecord($record);
        }

        if (!empty($messages)) {
            $this->send((string)$this->getFormatter()->formatBatch($messages), $messages);
        }
    }

    protected function write(array $record)
    {
        $this->send((string)$record['formatted'], array($record));
    }

    protected function send($message, array $records)
    {
        if (!$this->loaded) {
            $this->loadConfiguration();
        }
        $fp = fsockopen($this->getTransport(), $this->port, $errno, $errstr, 30);
        if (!$fp) {
            return false;
        }

        $request = "POST /inputs/" . $this->key . " HTTP/1.1\r\n";
        $request .= "Host: " . $this->host . "\r\n";
        $request .= "User-Agent: Billr \r\n";
        $request .= "Content-Type: application/json\r\n";
        $request .= "Content-Length: " . strlen($message) . "\r\n";
        $request .= "Connection: Close\r\n\r\n";
        $request .= $message;

        fwrite($fp, $request);
        fclose($fp);

        return true;
    }

    private function getTransport()
    {
        switch ($this->port) {
            case '443':
                return 'ssl://' . $this->host;
                break;
            case '80':
                return $this->host;
                break;
            default:
                throw new \LogicException('Allowed invalid port to be set.');
                break;
        }
    }

    protected function getDefaultFormatter()
    {
        return new JsonFormatter;
    }
}
