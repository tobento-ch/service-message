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

use Tobento\Service\Support\Arrayable;
use Stringable;

/**
 * Message
 */
class Message implements MessageInterface, Arrayable, Stringable
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
     */
    public function __construct(
        protected string $level,
        protected string $message,
        protected array $context = [],
        protected null|string $key = null,
        protected array $parameters = [],
        protected bool $logged = false,
    ) {}

    /**
     * Returns the level.
     *
     * @return string
     */
    public function level(): string
    {
        return $this->level;
    }
    
    /**
     * Returns a new instance with the specified level.
     *
     * @param string $level
     * @return static
     */
    public function withLevel(string $level): static
    {
        $new = clone $this;
        $new->level = $level;
        return $new;
    }

    /**
     * Returns the message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
    
    /**
     * Returns a new instance with the specified message.
     *
     * @param string $message
     * @return static
     */
    public function withMessage(string $message): static
    {
        $new = clone $this;
        $new->message = $message;
        return $new;
    }

    /**
     * Returns the context.
     *
     * @return array
     */
    public function context(): array
    {
        return $this->context;
    }
    
    /**
     * Returns a new instance with the specified context.
     *
     * @param array $context
     * @return static
     */
    public function withContext(array $context): static
    {
        $new = clone $this;
        $new->context = $context;
        return $new;
    }

    /**
     * Returns the key.
     *
     * @return null|string
     */
    public function key(): null|string
    {
        return $this->key;
    }
    
    /**
     * Returns a new instance with the specified key.
     *
     * @param string $key
     * @return static
     */
    public function withKey(string $key): static
    {
        $new = clone $this;
        $new->key = $key;
        return $new;
    }
    
    /**
     * Returns the parameter value.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */    
    public function parameter(string $name, mixed $default = null): mixed
    {
        return $this->parameters[$name] ?? $default;
    }
    
    /**
     * Returns the parameters.
     *
     * @return array<string, mixed>
     */    
    public function parameters(): array
    {
        return $this->parameters;
    }
    
    /**
     * Returns a new instance with the specified parameters.
     *
     * @param array $parameters
     * @return static
     */
    public function withParameters(array $parameters): static
    {
        $new = clone $this;
        $new->parameters = $parameters;
        return $new;
    }

    /**
     * Returns true if message has been logged, otherwise false.
     *
     * @return bool
     */
    public function logged(): bool
    {
        return $this->logged;
    }
    
    /**
     * Returns a new instance with the specified logged.
     *
     * @param bool $logged
     * @return static
     */
    public function withLogged(bool $logged): static
    {
        $new = clone $this;
        $new->logged = $logged;
        return $new;
    }
    
    /**
     * Returns the message.
     *
     * @return string
     */
    public function __toString(): string
    {
        return htmlspecialchars($this->message(), ENT_QUOTES, 'UTF-8', true);
    }

    /**
     * Object to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'level' => $this->level(),
            'message' => $this->message(),
            'context' => $this->context(),
            'key' => $this->key(),
            'parameters' => $this->parameters(),
            'logged' => $this->logged()
        ];
    }
    
    /**
     * __get For array_column object support
     */
    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    /**
     * __isset For array_column object support
     */
    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }
}