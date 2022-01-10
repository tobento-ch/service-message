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
use Tobento\Service\Message\Modifier\Pluralization;
use Tobento\Service\Message\Message;

/**
 * PluralizationTest tests
 */
class PluralizationTest extends TestCase
{    
    public function testThatImplementsModifierInterface()
    {
        $this->assertInstanceOf(
            ModifierInterface::class,
            new Pluralization()
        );     
    }

    public function testModifyUsesSingular()
    {
        $modifier = new Pluralization();

        $message = new Message(
            level: 'success',
            message: 'There is one apple|There are many apples',
            parameters: ['count' => 1],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'There is one apple',
            $newMessage->message()
        );
        
        $this->assertFalse($message === $newMessage);        
    }
 
    public function testModifyUsesSingularWithZeroCount()
    {
        $modifier = new Pluralization();

        $message = new Message(
            level: 'success',
            message: 'There is one apple|There are many apples',
            parameters: ['count' => 0],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'There is one apple',
            $newMessage->message()
        );   
    }
    
    public function testModifyUsesPlural()
    {
        $modifier = new Pluralization();

        $message = new Message(
            level: 'success',
            message: 'There is one apple|There are many apples',
            parameters: ['count' => 5],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'There are many apples',
            $newMessage->message()
        );        
    }

    public function testModifyWithoutCountReturnsFullMessage()
    {
        $modifier = new Pluralization();
        
        $message = new Message(
            level: 'success',
            message: 'There is one apple|There are many apples',
            parameters: [],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'There is one apple|There are many apples',
            $newMessage->message()
        );   
    }
    
    public function testModifyWithAnotherKey()
    {
        $modifier = new Pluralization('num');

        $message = new Message(
            level: 'success',
            message: 'There is one apple|There are many apples',
            parameters: ['num' => 5],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'There are many apples',
            $newMessage->message()
        );   
    }    
}