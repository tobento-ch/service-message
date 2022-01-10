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

namespace Tobento\Service\Message\Modifier;

use Tobento\Service\Translation\TranslatorInterface;
use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\MessageInterface;

/**
 * Translator
 */
class Translator implements ModifierInterface
{
    /**
     * Create a new Translator.
     *
     * @param TranslatorInterface $translator
     * @param string $src The resource name to be used for translation.
     */    
    public function __construct(
        protected TranslatorInterface $translator,
        protected string $src = '*',
    ) {}
    
    /**
     * Returns the modified message.
     *
     * @param MessageInterface The message to midify.
     * @return MessageInterface The modified message.
     */    
    public function modify(MessageInterface $message): MessageInterface
    {
        $parameters = $message->parameters();
        $parameters['src'] = $this->src;
        
        $translated = $this->translator->trans(
            message: $message->message(),
            parameters: $parameters,
        );
        
        return $message->withMessage($translated);
    }
}