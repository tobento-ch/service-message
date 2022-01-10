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
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\MessageInterface;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\ModifiersInterface;
use Tobento\Service\Message\MessageFactory;
use Tobento\Service\Message\Message;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

/**
 * MessagesLogTest
 */
class MessagesLogTest extends TestCase
{    
    public function testAddMethodShouldLog()
    {        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $messages = new Messages(logger: $logger);
        
        $this->assertFalse($testHandler->hasRecords('error'));
        
        $messages->add(
            level: 'error',
            message: 'Error message',
            log: true,
        );

        $this->assertTrue($testHandler->hasRecords('error'));
    }
    
    public function testAddMethodShouldNotLogIfset()
    {        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $messages = new Messages(logger: $logger);
        
        $this->assertFalse($testHandler->hasRecords('error'));
        
        $messages->add(
            level: 'error',
            message: 'Error message',
            log: false,
        );

        $this->assertFalse($testHandler->hasRecords('error'));
    }
    
    public function testAddMessageMethodShouldLog()
    {        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $messages = new Messages(logger: $logger);
        
        $this->assertFalse($testHandler->hasRecords('error'));
        
        $messages->addMessage(
            message: new Message('error', 'Error message'),
            log: true,
        );

        $this->assertTrue($testHandler->hasRecords('error'));
    }
    
    public function testAddMessageMethodShouldNotLogIfset()
    {        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $messages = new Messages(logger: $logger);
        
        $this->assertFalse($testHandler->hasRecords('error'));
        
        $messages->addMessage(
            message: new Message('error', 'Error message'),
            log: false,
        );

        $this->assertFalse($testHandler->hasRecords('error'));
    }
    
    public function testPushMethodShouldLog()
    {        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $someMessages = new Messages();
        $someMessages->add(level: 'error', message: 'Error message', log: true);

        $messages = new Messages(logger: $logger);
        $messages->add(level: 'success', message: 'Success message', log: false);
        
        $this->assertFalse($testHandler->hasRecords('error'));
        
        $messages->push(
            messages: $someMessages
        );        
        
        $this->assertTrue($testHandler->hasRecords('error'));
    }
    
    public function testPushMethodShouldLogOnceIfAlreadyLogged()
    {        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $someMessages = new Messages(logger: $logger);
        $someMessages->add(level: 'error', message: 'Error message', log: true);

        $messages = new Messages(logger: $logger);
        $messages->add(level: 'success', message: 'Success message', log: false);
        
        $messages->push(
            messages: $someMessages
        );
        
        $this->assertSame(
            1,
            count($testHandler->getRecords())
        );        
    }    
    
    public function testShouldNotLogIfAlreadyLogged()
    {        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $messages = new Messages(logger: $logger);
        
        $this->assertFalse($testHandler->hasRecords('error'));
        
        $msg = new Message(
            level: 'error',
            message: 'Error message',
            logged: true,
        );
        
        $messages->addMessage(
            message: $msg,
            log: true,
        );

        $this->assertFalse($testHandler->hasRecords('error'));
    }    
}