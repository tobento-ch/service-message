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
use Tobento\Service\Message\Modifier\LimitLength;
use Tobento\Service\Message\Message;

/**
 * LimitLengthTest
 */
class LimitLengthTest extends TestCase
{    
    public function testThatImplementsModifierInterface()
    {
        $this->assertInstanceOf(
            ModifierInterface::class,
            new LimitLength()
        );     
    }

    public function testModify()
    {
        $modifier = new LimitLength(
            length: 15,
            parameterKey: 'limit_length',
        );

        $message = new Message(
            level: 'success',
            message: 'Some very long message',
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'Some very lo...',
            $newMessage->message()
        );
        
        $this->assertFalse($message === $newMessage);
    }
    
    public function testModifyUsesParameterInsteadOfDefault()
    {
        $modifier = new LimitLength(
            length: 15,
            parameterKey: 'limit_length',
        );

        $message = new Message(
            level: 'success',
            message: 'Some very long message',
            parameters: [
                // used instead of default if set.
                'limit_length' => 10,
            ],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'Some ve...',
            $newMessage->message()
        );
    }
    
    public function testModifyWithDifferentParameterName()
    {
        $modifier = new LimitLength(
            length: 15,
            parameterKey: 'limit_len',
        );

        $message = new Message(
            level: 'success',
            message: 'Some very long message',
            parameters: [
                // used instead of default if set.
                'limit_len' => 10,
            ],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'Some ve...',
            $newMessage->message()
        );
    }     
}