<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 04/09/13
 * Time: 20:43
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Listeners;


use Doctrine\ORM\EntityManager;
use Maxim\CMSBundle\Entity\Purchase;
use Maxim\CMSBundle\Event\MinecraftSendEvent;
use Maxim\CMSBundle\Exception\CommandExecutionException;
use Maxim\CMSBundle\Helper\MinecraftHelper;
use Monolog\Logger;

class MinecraftSendListener {

    private $logger;
    protected $minecraft;
    private $em;

    private $host = "192.99.9.79";
    private $port = 25565;

    public function __construct(Logger $logger, MinecraftHelper $minecraft, EntityManager $em)
    {
        $this->logger = $logger;
        $this->minecraft = $minecraft;
        $this->em = $em;
    }

    /**
     * var @Purchase $purchase
     */
    public function onMinecraftSend(MinecraftSendEvent $event)
    {
        try {
            error_reporting(E_ALL);

            /* Create a TCP/IP socket. */
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if($socket === false) {
                throw new \Exception(socket_strerror(socket_last_error()));
            }

            # Connect on address on port
            $result = socket_connect($socket, $this->host, $this->port);
            if($result === false) {
                throw new \Exception(socket_strerror(socket_last_error($socket)));
            }

            # send package

            //$msg = "10000d" . $event->getUsername() . ";" . $event->getCommand();

            foreach($event->getPurchases() as $purchase)
            {
                //$pdo->query($this->minecraft->parseCommand($item->getCommand(), array("USER" => $purchase->getName())));
                $command = $this->minecraft->parseCommand($purchase->getStoreItem()->getCommand(), array("USER" => $purchase->getName(), "UUID" => $purchase->getUser()->getMcUuid()));

                $this->logger->info("[SOCKET]command: " . $command);
                $this->logger->info("[SOCKET]Wrote command: " . $command);

                if(socket_write($socket, $command, strlen($command)))
                {
                    $purchase->setStatus(Purchase::PURCHASE_COMPLETE);
                    $purchase->setItemDelivery(Purchase::ITEM_DELIVERY_SUCCESS);
                }
                else
                {
                    $purchase->setStatus(Purchase::PURCHASE_ERROR_COMMAND);
                    $purchase->setItemDelivery(Purchase::ITEM_DELIVERY_FAILED);
                }
            }

            //$buffer = socket_read($socket, 256, PHP_NORMAL_READ);
            //echo $buffer;

            //$out = socket_read($socket, 256);

            # Close socket and cleanup
            socket_close($socket);
            $result = null;
            $socket = null;

        }catch(\Exception $ex) {
            $this->logger->err($ex->getMessage());
            throw new CommandExecutionException("[COMMAND]Could not execute command: " . $ex->getMessage());
        }
    }
}