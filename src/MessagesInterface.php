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

use Psr\Log\LoggerInterface;
use IteratorAggregate;

/**
 * MessagesInterface
 */
interface MessagesInterface extends IteratorAggregate
{
    /**
     * Returns a new instance with the specified message factory.
     * 
     * @param MessageFactoryInterface $messageFactory
     * @return static
     */         
    public function withMessageFactory(MessageFactoryInterface $messageFactory): static;
    
    /**
     * Returns the message factory.
     * 
     * @return MessageFactoryInterface
     */         
    public function messageFactory(): MessageFactoryInterface;
    
    /**
     * Returns a new instance with the specified modifiers.
     * 
     * @param ModifiersInterface $modifiers
     * @return static
     */         
    public function withModifiers(ModifiersInterface $modifiers): static;
    
    /**
     * Returns the modifiers.
     * 
     * @return ModifiersInterface
     */         
    public function modifiers(): ModifiersInterface;
    
    /**
     * Returns a new instance with the specified logger.
     * 
     * @param null|LoggerInterface $logger
     * @return static
     */         
    public function withLogger(null|LoggerInterface $logger): static;
    
    /**
     * Returns the logger or null if none.
     *
     * @return null|LoggerInterface
     */    
    public function logger(): null|LoggerInterface;
    
    /**
     * Returns a new instance with the specified message(s).
     * 
     * @param MessageInterface ...$messages
     * @return static
     */         
    public function withMessage(MessageInterface ...$messages): static;

    /**
     * Adds a message.
     * 
     * @param MessageInterface $message
     * @param bool $log If to log the message.
     * @return static $this
     */         
    public function addMessage(MessageInterface $message, bool $log = true): static;
    
    /**
     * Add a message.
     *
     * @param string $level The level such as 'info'.
     * @param string $message The message.
     * @param array $context Any message context.
     * @param null|string $key A key for input fields for example.
     * @param array<string, mixed> $parameters Any parameters.
     * @param bool $log If to log the message.
     * @return static $this
     */
    public function add(
        string $level,
        string $message,
        array $context = [],
        null|string $key = null,
        array $parameters = [],
        bool $log = true,
    ): static;
    
    /**
     * Push messages.
     *
     * @param array<int, mixed>|MessagesInterface $messages
     * @return static $this
     */
    public function push(array|MessagesInterface $messages): static;
    
    /**
     * Get the column of the messages.
     *
     * @param string $column The column such as 'message'.
     * @param null|string $index The index such as 'key'.
     * @return array
     */
    public function column(string $column, null|string $index = null): array;

    /**
     * Returns a new instance with the filtered messages.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static;
    
    /**
     * Returns a new instance with the specified key messages filtered.
     *
     * @param string $key
     * @return static
     */
    public function key(string $key): static;
        
    /**
     * Returns a new instance with messages with only the levels specified.
     *
     * @param array $levels
     * @return static
     */
    public function only(array $levels): static;
        
    /**
     * Returns a new instance with messages except the level specified.
     *
     * @param array $levels
     * @return static
     */
    public function except(array $levels): static;

    /**
     * Get first message.
     *
     * @return null|MessageInterface
     */
    public function first(): null|MessageInterface;

    /**
     * Get last message.
     *
     * @return null|MessageInterface
     */
    public function last(): null|MessageInterface;
    
    /**
     * Returns all messages. 
     *
     * @return array<int, MessageInterface>
     */
    public function all(): array;
    
    /**
     * Returns true if messages exists, otherwise false.
     *
     * @param null|array $levels The message levels such as ['info', 'debug']
     * @return bool
     */
    public function has(null|array $levels = null): bool;    
}