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
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\ModifiersInterface;
use Tobento\Service\Message\Modifier\ParameterReplacer;
use Tobento\Service\Message\Modifier\Pluralization;

/**
 * ModifiersTest
 */
class ModifiersTest extends TestCase
{    
    public function testThatImplementsModifiersInterface()
    {
        $this->assertInstanceOf(
            ModifiersInterface::class,
            new Modifiers(
                new ParameterReplacer(),
            )
        );     
    }

    public function testAddMethod()
    {
        $modifiers = new Modifiers();
        $modifier = new ParameterReplacer();       
        $modifiers->add($modifier);
            
        $this->assertSame(
            $modifier,
            $modifiers->all()[0]
        );
    }
    
    public function testPrependMethod()
    {
        $modifiers = new Modifiers();
        $modifier = new ParameterReplacer();       
        $modifiers->add($modifier);
        
        $plural = new Pluralization();
        
        $modifiers->prepend($plural);
        
        $this->assertSame(
            $plural,
            $modifiers->all()[0]
        );
    }    
    
    public function testAllMethod()
    {
        $modifiers = new Modifiers(new Pluralization());
        $modifier = new ParameterReplacer();       
        $modifiers->add($modifier);
            
        $this->assertSame(
            2,
            count($modifiers->all())
        );
    }

    public function testModifyMethod()
    {
        $modifiers = new Modifiers(new ParameterReplacer());

        $message = new Message(
            level: 'success',
            message: 'Hello :name, welcome back',
            parameters: [':name' => 'John'],
        );
        
        $newMessage = $modifiers->modify($message);
        
        $this->assertSame(
            'Hello John, welcome back',
            $newMessage->message()
        );
        
        $this->assertFalse($message === $newMessage);
    }      
}