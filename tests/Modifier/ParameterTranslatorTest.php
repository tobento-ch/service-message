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
use Tobento\Service\Message\Modifier\ParameterTranslator;
use Tobento\Service\Message\Message;
use Tobento\Service\Translation;

/**
 * ParameterTranslatorTest
 */
class ParameterTranslatorTest extends TestCase
{
    protected function getTranslator()
    {
        return new Translation\Translator(
            new Translation\Resources(
                new Translation\Resource('*', 'en', [
                    'title' => 'title',
                ]),
                new Translation\Resource('*', 'de', [
                    'title' => 'Titel',
                ]),
            ),
            new Translation\Modifiers(
                new Translation\Modifier\Pluralization(),        
                new Translation\Modifier\ParameterReplacer(),
            ),
            new Translation\MissingTranslationHandler(),
            'de',
        );  
    }
    
    public function testThatImplementsModifierInterface()
    {
        $this->assertInstanceOf(
            ModifierInterface::class,
            new ParameterTranslator([], $this->getTranslator())
        );
    }

    public function testModify()
    {
        $modifier = new ParameterTranslator(
            parameters: [':attribute'],
            translator: $this->getTranslator(),
            src: '*',
        );

        $message = new Message(
            level: 'error',
            message: 'The :attribute is invalid',
            parameters: [':attribute' => 'title'],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'The :attribute is invalid',
            $newMessage->message()
        );
        
        $this->assertSame(
            'Titel',
            $newMessage->parameters()[':attribute']
        );        
        
        $this->assertFalse($message === $newMessage);
    }    
}