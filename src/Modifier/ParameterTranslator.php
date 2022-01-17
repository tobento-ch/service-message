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
 * ParameterTranslator
 */
class ParameterTranslator implements ModifierInterface
{
    /**
     * Create a new ParameterTranslator.
     *
     * @param array $parameters The message parameters to translate.
     * @param TranslatorInterface $translator
     * @param string $src The resource name to be used for translation.
     */    
    public function __construct(
        protected array $parameters,
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
        
        foreach($this->parameters as $parameter)
        {
            if (
                isset($parameters[$parameter])
                && is_string($parameters[$parameter])
            ) {
                
                $parameters[$parameter] = $this->translator->trans(
                    message: $parameters[$parameter],
                    parameters: $parameters,
                );                
            }
        }
        
        return $message->withParameters($parameters);
    }
}