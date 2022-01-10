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
use Tobento\Service\Message\Message;
use Tobento\Service\Message\MessageInterface;

/**
 * MessageTest
 */
class MessageTest extends TestCase
{    
    public function testThatImplementsMessageInterface()
    {
        $this->assertInstanceOf(
            MessageInterface::class,
            new Message('error', 'message')
        );
    }
    
    public function testGetMethods()
    {
        $message = new Message(
            level: 'success',
            message: 'Hello :name, welcome back',
            context: ['logged_in' => 'John'],
            key: 'user.name', // null|string
            parameters: [':name' => 'John'],
            logged: false,
        );
        
        $this->assertSame(
            'success',
            $message->level()
        );
        
        $this->assertSame(
            'Hello :name, welcome back',
            $message->message()
        );
        
        $this->assertSame(
            ['logged_in' => 'John'],
            $message->context()
        );
        
        $this->assertSame(
            'user.name',
            $message->key()
        );
        
        $this->assertSame(
            [':name' => 'John'],
            $message->parameters()
        );
        
        $this->assertSame(
            false,
            $message->logged()
        );        
    }
    
    public function testParameterMethod()
    {
        $message = new Message(
            level: 'success',
            message: 'Hello :name, welcome back',
            parameters: [':name' => 'John'],
        );
        
        $this->assertSame(
            'John',
            $message->parameter(':name')
        );
        
        $this->assertSame(
            null,
            $message->parameter('foo')
        );
        
        $this->assertSame(
            'default',
            $message->parameter('foo', 'default')
        );
    }
        
    public function testWithLevelMethod()
    {
        $message = new Message('success', 'message');
        
        $newMessage = $message->withLevel('error');
        
        $this->assertFalse($message === $newMessage);
        $this->assertSame('success', $message->level());
        $this->assertSame('error', $newMessage->level());
    }
    
    public function testWithMessageMethod()
    {
        $message = new Message('success', 'message');
        
        $newMessage = $message->withMessage('new message');
        
        $this->assertFalse($message === $newMessage);
        $this->assertSame('message', $message->message());
        $this->assertSame('new message', $newMessage->message());
    }
    
    public function testWithContextMethod()
    {
        $message = new Message('success', 'message');
        
        $newMessage = $message->withContext(['foo']);
        
        $this->assertFalse($message === $newMessage);
        $this->assertSame([], $message->context());
        $this->assertSame(['foo'], $newMessage->context());
    }
    
    public function testWithKeyMethod()
    {
        $message = new Message('success', 'message');
        
        $newMessage = $message->withKey('foo');
        
        $this->assertFalse($message === $newMessage);
        $this->assertSame(null, $message->key());
        $this->assertSame('foo', $newMessage->key());
    }
    
    public function testWithParametersMethod()
    {
        $message = new Message('success', 'message');
        
        $newMessage = $message->withParameters(['foo']);
        
        $this->assertFalse($message === $newMessage);
        $this->assertSame([], $message->parameters());
        $this->assertSame(['foo'], $newMessage->parameters());
    }
    
    public function testWithLoggedMethod()
    {
        $message = new Message('success', 'message');
        
        $newMessage = $message->withLogged(true);
        
        $this->assertFalse($message === $newMessage);
        $this->assertSame(false, $message->logged());
        $this->assertSame(true, $newMessage->logged());
    }
    
    public function testRenderMessage()
    {
        $message = new Message('success', '<p>message</p>');
        
        $this->assertSame('<p>message</p>', $message->message());
        $this->assertSame('&lt;p&gt;message&lt;/p&gt;', (string)$message);
    }
    
    public function testToArrayMessage()
    {
        $message = new Message(
            level: 'success',
            message: 'Hello :name, welcome back',
            context: ['logged_in' => 'John'],
            key: 'user.name',
            parameters: [':name' => 'John'],
            logged: false,
        );
        
        $this->assertSame(
            [
                'level' => 'success',
                'message' => 'Hello :name, welcome back',
                'context' => ['logged_in' => 'John'],
                'key' => 'user.name',
                'parameters' => [':name' => 'John'],
                'logged' => false,         
            ],
            $message->toArray()
        );
    }
}