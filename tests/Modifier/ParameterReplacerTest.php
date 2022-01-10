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

namespace Tobento\Service\Message\Test\Modifier;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\Modifier\ParameterReplacer;
use Tobento\Service\Message\Message;

/**
 * ParameterReplacerTest tests
 */
class ParameterReplacerTest extends TestCase
{    
    public function testThatImplementsModifierInterface()
    {
        $this->assertInstanceOf(
            ModifierInterface::class,
            new ParameterReplacer()
        );     
    }

    public function testModify()
    {
        $modifier = new ParameterReplacer();

        $message = new Message(
            level: 'success',
            message: 'Hello :name, welcome back',
            parameters: [':name' => 'John'],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'Hello John, welcome back',
            $newMessage->message()
        );
        
        $this->assertFalse($message === $newMessage);
    }
    
    public function testReplaceWithoutSpacesShouldReplace()
    {
        $modifier = new ParameterReplacer();

        $message = new Message(
            level: 'success',
            message: 'Hi:name, welcome back',
            parameters: [':name' => 'John'],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'HiJohn, welcome back',
            $newMessage->message()
        );      
    }
    
    public function testMultipleReplaces()
    {
        $modifier = new ParameterReplacer();

        $message = new Message(
            level: 'success',
            message: 'Hi :name. In :minutes minutes.',
            parameters: [':name' => 'John', ':minutes' => 5],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'Hi John. In 5 minutes.',
            $newMessage->message()
        );      
    }
    
    public function testMissingParameter()
    {
        $modifier = new ParameterReplacer();

        $message = new Message(
            level: 'success',
            message: 'Hi :name. In :minutes minutes.',
            parameters: [':name' => 'John'],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'Hi John. In :minutes minutes.',
            $newMessage->message()
        );      
    }
    
    public function testWithOtherIndicator()
    {
        $modifier = new ParameterReplacer('|');

        $message = new Message(
            level: 'success',
            message: 'Hi |name',
            parameters: ['|name' => 'John'],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'Hi John',
            $newMessage->message()
        );      
    }    
}