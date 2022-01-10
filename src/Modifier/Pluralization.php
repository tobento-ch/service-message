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

use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\MessageInterface;

/**
 * Pluralization
 */
class Pluralization implements ModifierInterface
{
    /**
     * Create a new Pluralization.
     *
     * @param string $key The parameter key 
     */    
    public function __construct(
        protected string $key = 'count'
    ) {}
    
    /**
     * Returns the modified message.
     *
     * @param MessageInterface The message to midify.
     * @return MessageInterface The modified message.
     */    
    public function modify(MessageInterface $message): MessageInterface
    {
        if (empty($message->parameters())) {
            return $message;
        }
        
        if (! array_key_exists($this->key, $message->parameters())) {
            return $message;
        }
        
        $parameters = $message->parameters();
        
        // Has count.        
        $messages = explode('|', $message->message());
        $count = (int) $parameters[$this->key];
        
        if ($count > 1) { // plural
            $message = $message->withMessage($messages[1] ?? $messages[0]);
        } else {
            $message = $message->withMessage($messages[0]);
        }
        
        unset($parameters[$this->key]);
        
        return $message->withParameters($parameters);
    }
}