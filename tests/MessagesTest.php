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
 * MessagesTest
 */
class MessagesTest extends TestCase
{    
    public function testThatImplementsMessagesInterface()
    {
        $this->assertInstanceOf(
            MessagesInterface::class,
            new Messages()
        );
    }
    
    public function testCreateMessages()
    {
        $messages = new Messages(
            messageFactory: new MessageFactory(),
            modifiers: new Modifiers(),
            logger: null,
        );
        
        $this->assertTrue(true);
    }
    
    public function testMessageFactoryMethod()
    {
        $messages = new Messages();

        $this->assertInstanceOf(
            MessageFactoryInterface::class,
            $messages->messageFactory()
        );
        
        $messageFactory = new MessageFactory();
        
        $messages = new Messages($messageFactory);

        $this->assertSame(
            $messageFactory,
            $messages->messageFactory()
        );        
    }    
    
    public function testWithMessageFactoryMethod()
    {
        $messages = new Messages();
        
        $newMessages = $messages->withMessageFactory(
            messageFactory: new MessageFactory()
        );
        
        $this->assertFalse($messages === $newMessages);
    }
    
    public function testModifiersMethod()
    {
        $messages = new Messages();

        $this->assertInstanceOf(
            ModifiersInterface::class,
            $messages->modifiers()
        );
        
        $modifiers = new Modifiers();
        
        $messages = new Messages(modifiers: $modifiers);

        $this->assertSame(
            $modifiers,
            $messages->modifiers()
        );        
    }    
    
    public function testWithModifiersMethod()
    {
        $messages = new Messages();
        
        $newMessages = $messages->withModifiers(
            modifiers: new Modifiers()
        );
        
        $this->assertFalse($messages === $newMessages);
    }
    
    public function testLoggerMethod()
    {
        $messages = new Messages();

        $this->assertSame(
            null,
            $messages->logger()
        );
        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $messages = new Messages(logger: $logger);

        $this->assertSame(
            $logger,
            $messages->logger()
        );        
    }
    
    public function testWithLoggerMethod()
    {
        $messages = new Messages();
        
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $newMessages = $messages->withLogger(
            logger: $logger
        );
        
        $this->assertFalse($messages === $newMessages);
    }
    
    public function testAddMessageMethod()
    {
        $messages = new Messages();
        
        $messages->addMessage(
            message: new Message('error', 'message'),
            log: false,
        )->addMessage(
            message: new Message('error', 'message'),
            log: false,
        );        

        $this->assertSame(
            2,
            count($messages->all())
        );
    }
    
    public function testAddMethod()
    {
        $messages = new Messages();

        $messages->add(
            level: 'error',
            message: 'Error message',
            context: [],
            key: null, // null|string
            parameters: [],
            log: false,
        )->add(
            level: 'error',
            message: 'Error message',
            context: [],
            key: null, // null|string
            parameters: [],
            log: false,
        );

        $this->assertSame(
            2,
            count($messages->all())
        );        
    }
    
    public function testPushMethod()
    {
        $someMessages = new Messages();
        $someMessages->add('error', 'Error message');

        $messages = new Messages();
        $messages->add('success', 'Success message');

        $messages->push(
            messages: $someMessages
        );

        $this->assertSame(
            2,
            count($messages->all())
        );        
    }
    
    public function testPushMethodWithArray()
    {
        $messages = new Messages();
        $messages->add('success', 'Success message');

        $messages->push(
            messages: [
                ['level' => 'info', 'message' => 'Some info'],
            ]
        );

        $this->assertSame(
            2,
            count($messages->all())
        );        
    }
    
    public function testWithMessageMethod()
    {
        $someMessages = new Messages();
        $someMessages->add('error', 'Error message');

        $messages = new Messages();
        $messages->add('success', 'Success message');

        $newMessages = $messages->withMessage(
            ...$someMessages
        );
        
        $this->assertFalse($messages === $newMessages);
        
        $this->assertSame(
            1,
            count($newMessages->all())
        );        
    }
    
    public function testFilterMethod()
    {
        $messages = new Messages();
        $messages->add('success', 'Success message');
        $messages->add('error', 'Error message');

        $newMessages = $messages->filter(
            fn(MessageInterface $m): bool => $m->level() === 'error'
        );
        
        $this->assertFalse($messages === $newMessages);
        
        $this->assertSame(
            1,
            count($newMessages->all())
        );        
    }
    
    public function testKeyMethod()
    {
        $messages = new Messages();
        $messages->add(level: 'success', message: 'Success message', key: 'foo');
        $messages->add('error', 'Error message');

        $newMessages = $messages->key('foo');
        
        $this->assertFalse($messages === $newMessages);
        
        $this->assertSame(
            1,
            count($newMessages->all())
        );        
    }
    
    public function testOnlyMethod()
    {
        $messages = new Messages();
        $messages->add('success', 'Success message');
        $messages->add('error', 'Error message');
        $messages->add('info', 'Info message');

        $newMessages = $messages->only(levels: ['info', 'success']);
        
        $this->assertFalse($messages === $newMessages);
        
        $this->assertSame(
            2,
            count($newMessages->all())
        );        
    }
    
    public function testExceptMethod()
    {
        $messages = new Messages();
        $messages->add('success', 'Success message');
        $messages->add('error', 'Error message');
        $messages->add('info', 'Info message');

        $newMessages = $messages->except(levels: ['info', 'success']);
        
        $this->assertFalse($messages === $newMessages);
        
        $this->assertSame(
            1,
            count($newMessages->all())
        );        
    }
    
    public function testAllMethod()
    {
        $messages = new Messages();
        $messages->add('success', 'Success message');
        $messages->add('error', 'Error message');
        $messages->add('info', 'Info message');
        
        $this->assertSame(
            3,
            count($messages->all())
        );        
    }
    
    public function testFirstMethod()
    {
        $messages = new Messages();
        $success = new Message('success', 'message');
        $error = new Message('error', 'message');
        
        $messages->addMessage(
            message: $success,
        )->addMessage(
            message: $error,
        );
        
        $this->assertSame(
            $success,
            $messages->first()
        );        
    }
    
    public function testLastMethod()
    {
        $messages = new Messages();
        $success = new Message('success', 'message');
        $error = new Message('error', 'message');
        
        $messages->addMessage(
            message: $success,
        )->addMessage(
            message: $error,
        );
        
        $this->assertSame(
            $error,
            $messages->last()
        );        
    }
    
    public function testColumnMethod()
    {
        $messages = new Messages();
        $messages->add('success', 'Success message');
        $messages->add('error', 'Error message');

        $values = $messages->column(
            column: 'message',
            index: 'level',
        );
        
        $this->assertSame(
            [
                'success' => 'Success message',
                'error' => 'Error message',
            ],
            $values
        );        
    }
    
    public function testHasMethod()
    {    
        $messages = new Messages();
        
        $this->assertFalse($messages->has());
        
        $messages->add('success', 'Success message');
        $messages->add('error', 'Error message');
        
        $this->assertTrue($messages->has());
        $this->assertTrue($messages->has(levels: ['error', 'success']));
        $this->assertFalse($messages->has(levels: ['error', 'info']));
    }    
}