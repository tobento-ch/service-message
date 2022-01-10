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
use Tobento\Service\Message\MessageFactory;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\MessageInterface;

/**
 * MessageFactoryTest
 */
class MessageFactoryTest extends TestCase
{    
    public function testThatImplementsMessageFactoryInterface()
    {
        $this->assertInstanceOf(
            MessageFactoryInterface::class,
            new MessageFactory()
        );
    }
    
    public function testCreateMessageMethod()
    {
        $messageFactory = new MessageFactory();

        $message = $messageFactory->createMessage(
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
    
    public function testCreateMessageFromArrayMethod()
    {
        $messageFactory = new MessageFactory();

        $message = $messageFactory->createMessageFromArray([
            'level' => 'success',
            'message' => 'Hello :name, welcome back',
            'context' => ['logged_in' => 'John'],
            'key' => 'user.name',
            'parameters' => [':name' => 'John'],
            'logged' => false,
        ]);
        
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