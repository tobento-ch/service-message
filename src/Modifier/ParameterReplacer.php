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
 * ParameterReplacer
 */
class ParameterReplacer implements ModifierInterface
{
    /**
     * Create a new ParameterReplacer.
     *
     * @param string $indicator
     */    
    public function __construct(
        protected string $indicator = ':'
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
        
        $replace = [];
        
        foreach($message->parameters() as $key => $value)
        {
            if (str_starts_with($key, $this->indicator)) {
                $replace[$key] = $value;
            }
        }
        
        // Do the replacements.
        return $message->withMessage(
            strtr($message->message(), $replace)
        );
    }
}