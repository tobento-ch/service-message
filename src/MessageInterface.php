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

/**
 * MessageInterface
 */
interface MessageInterface
{
    /**
     * Returns the level.
     *
     * @return string
     */
    public function level(): string;
    
    /**
     * Returns a new instance with the specified level.
     *
     * @param string $level
     * @return static
     */
    public function withLevel(string $level): static;

    /**
     * Returns the message.
     *
     * @return string
     */
    public function message(): string;
    
    /**
     * Returns a new instance with the specified message.
     *
     * @param string $message
     * @return static
     */
    public function withMessage(string $message): static;

    /**
     * Returns the context.
     *
     * @return array
     */
    public function context(): array;
    
    /**
     * Returns a new instance with the specified context.
     *
     * @param array $context
     * @return static
     */
    public function withContext(array $context): static;

    /**
     * Returns the key.
     *
     * @return null|string
     */
    public function key(): null|string;
    
    /**
     * Returns a new instance with the specified key.
     *
     * @param string $key
     * @return static
     */
    public function withKey(string $key): static;
    
    /**
     * Returns the parameter value.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */    
    public function parameter(string $name, mixed $default = null): mixed;
    
    /**
     * Returns the parameters.
     *
     * @return array<string, mixed>
     */    
    public function parameters(): array;
    
    /**
     * Returns a new instance with the specified parameters.
     *
     * @param array $parameters
     * @return static
     */
    public function withParameters(array $parameters): static;

    /**
     * Returns true if message has been logged, otherwise false.
     *
     * @return bool
     */
    public function logged(): bool;
    
    /**
     * Returns a new instance with the specified logged.
     *
     * @param bool $logged
     * @return static
     */
    public function withLogged(bool $logged): static;
}