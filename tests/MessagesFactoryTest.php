<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Message\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Message\MessagesFactory;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\MessageFactory;
use Tobento\Service\Message\Modifiers;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

/**
 * MessagesFactoryTest
 */
class MessagesFactoryTest extends TestCase
{    
    public function testThatImplementsMessagesFactoryInterface()
    {
        $this->assertInstanceOf(
            MessagesFactoryInterface::class,
            new MessagesFactory()
        );
    }
    
    public function testCreateMessagesMethod()
    {
        $messagesFactory = new MessagesFactory(
            messageFactory: null,
            modifiers: null,
            logger: null,
        );

        $messages = $messagesFactory->createMessages();
        
        $this->assertInstanceOf(
            MessagesInterface::class,
            $messages
        );  
    }
    
    public function testCreateMessagesFactory()
    {
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $messagesFactory = new MessagesFactory(
            messageFactory: new MessageFactory(),
            modifiers: new Modifiers(),
            logger: $logger,
        );

        $messages = $messagesFactory->createMessages();
        
        $this->assertInstanceOf(
            MessagesInterface::class,
            $messages
        );  
    }     
}