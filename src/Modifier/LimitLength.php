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
 * LimitLength
 */
class LimitLength implements ModifierInterface
{
    /**
     * Create a new LengthLimit.
     *
     * @param null|int $length
     * @param string $parameterKey
     */    
    public function __construct(
        protected null|int $length = null,
        protected string $parameterKey = 'limit_length',
    ) {}
    
    /**
     * Returns the modified message.
     *
     * @param MessageInterface The message to midify.
     * @return MessageInterface The modified message.
     */    
    public function modify(MessageInterface $message): MessageInterface
    {
        $length = $message->parameters()[$this->parameterKey] ?? $this->length;
        
        if (!is_int($length)) {
            return $message;
        }
        
        if (strlen($message->message()) <= $length) {
            return $message;
        }
        
        return $message->withMessage(
            substr($message->message(), 0, $length - 3) . '...'
        );
    }
}