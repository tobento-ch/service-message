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
use Tobento\Service\Message\Modifier\Translator;
use Tobento\Service\Message\Message;
use Tobento\Service\Translation;

/**
 * TranslatorTest
 */
class TranslatorTest extends TestCase
{
    protected function getTranslator()
    {
        return new Translation\Translator(
            new Translation\Resources(
                new Translation\Resource('*', 'de', [
                    'Some error occured' => 'Some error occured',
                ]),
                new Translation\Resource('*', 'de', [
                    'Some error occured' => 'Ein Fehler is passiert',
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
            new Translator(translator: $this->getTranslator())
        );
    }

    public function testModify()
    {
        $modifier = new Translator(
            translator: $this->getTranslator(),
            src: '*',
        );

        $message = new Message(
            level: 'error',
            message: 'Some error occured',
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            'Ein Fehler is passiert',
            $newMessage->message()
        );
        
        $this->assertFalse($message === $newMessage);
    }    
}