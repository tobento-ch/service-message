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

namespace Tobento\Service\Message;

use Tobento\Service\Collection\Collection;

/**
 * MessageFactory
 */
class MessageFactory implements MessageFactoryInterface
{
    /**
     * Create a new Message.
     *
     * @param string $level The level such as 'info'.
     * @param string $message The message.
     * @param array $context Any message context.
     * @param null|string $key A key for input fields for example.
     * @param array<string, mixed> $parameters Any parameters.
     * @param bool $logged If message has been logged.
     * @return MessageInterface
     */
    public function createMessage(
        string $level,
        string $message,
        array $context = [],
        null|string $key = null,
        array $parameters = [],
        bool $logged = false,
    ): MessageInterface {
        
        $level = strtolower($level);
        
        return new Message($level, $message, $context, $key, $parameters, $logged);
    }
    
    /**
     * Create a new Message from array.
     *
     * @param array $message
     * @return MessageInterface
     */
    public function createMessageFromArray(array $message): MessageInterface
    {
        $message = new Collection($message);
        
        $key = $message->get('key', '');
        
        if (empty($key)) {
            $key = null;
        }
        
        return $this->createMessage(
            $message->get('level', 'error'),
            $message->get('message', 'Error message'),
            $message->get('context', []),
            $key,
            $message->get('parameters', []),
            $message->get('logged', false)
        );
    }
}