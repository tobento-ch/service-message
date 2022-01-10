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
use Tobento\Service\Collection\Collection;
use Tobento\Service\Support\Arrayable;
use ArrayIterator;
use Traversable;

/**
 * Messages
 */
class Messages implements MessagesInterface, Arrayable
{
    /**
     * @var MessageFactoryInterface
     */
    protected MessageFactoryInterface $messageFactory;
    
    /**
     * @var array<int, MessageInterface>
     */
    protected array $messages = [];
    
    /**
     * @var array<string, string> Maps the levels to the logger levels.
     */
    protected array $logLevels = [
        'emergency' => 'emergency',
        'alert' => 'alert',
        'critical' => 'critical',
        'error' => 'error',
        'warning' => 'warning',
        'notice' => 'notice',
        'info' => 'info',
        'debug' => 'debug',
        'success' => 'info',
    ];    
    
    /**
     * Create a new Messages.
     *
     * @param null|MessageFactoryInterface $messageFactory
     * @param null|ModifiersInterface $modifiers
     * @param null|LoggerInterface $logger
     * @param MessageInterface ...$messages
     */    
    public function __construct(
        null|MessageFactoryInterface $messageFactory = null,
        protected null|ModifiersInterface $modifiers = null,
        protected null|LoggerInterface $logger = null,
        MessageInterface ...$messages,
    ) {
        $this->messageFactory = $messageFactory ?: new MessageFactory();
        
        foreach($messages as $message) {
            $this->addMessage($message);
        }
    }

    /**
     * Returns a new instance with the specified message factory.
     * 
     * @param MessageFactoryInterface $messageFactory
     * @return static
     */         
    public function withMessageFactory(MessageFactoryInterface $messageFactory): static
    {
        $new = clone $this;
        $new->messageFactory = $messageFactory;
        return $new;
    }
    
    /**
     * Returns the message factory.
     * 
     * @return MessageFactoryInterface
     */         
    public function messageFactory(): MessageFactoryInterface
    {
        return $this->messageFactory;
    }
    
    /**
     * Returns a new instance with the specified modifiers.
     * 
     * @param ModifiersInterface $modifiers
     * @return static
     */         
    public function withModifiers(ModifiersInterface $modifiers): static
    {
        $new = clone $this;
        $new->modifiers = $modifiers;
        return $new;
    }
    
    /**
     * Returns the modifiers.
     * 
     * @return ModifiersInterface
     */         
    public function modifiers(): ModifiersInterface
    {
        if (is_null($this->modifiers)) {
             $this->modifiers = new Modifiers();   
        }
        
        return $this->modifiers;
    }    
    
    /**
     * Returns a new instance with the specified logger.
     * 
     * @param null|LoggerInterface $logger
     * @return static
     */         
    public function withLogger(null|LoggerInterface $logger): static
    {
        $new = clone $this;
        $new->logger = $logger;
        return $new;
    }
    
    /**
     * Returns the logger or null if none.
     *
     * @return null|LoggerInterface
     */    
    public function logger(): null|LoggerInterface
    {
        return $this->logger;
    }    
    
    /**
     * Returns a new instance with the specified message(s).
     * 
     * @param MessageInterface ...$messages
     * @return static
     */         
    public function withMessage(MessageInterface ...$messages): static
    {
        $new = clone $this;
        $new->messages = [];
        
        foreach($messages as $message) {
            $new->addMessage($message);
        }
        
        return $new;
    }

    /**
     * Adds a message.
     * 
     * @param MessageInterface $message
     * @param bool $log If to log the message.
     * @return static $this
     */         
    public function addMessage(MessageInterface $message, bool $log = true): static
    {
        if ($this->modifiers) {
            $message = $this->modifiers->modify($message);
        }
        
        if (
            $this->logger
            && $log === true
            && ! $message->logged()
            && array_key_exists($message->level(), $this->logLevels)
        ) {
            $this->logger->log(
                $this->logLevels[$message->level()],
                $message->message(),
                $message->context()
            );
            
            $message = $message->withLogged(true);
        }
                
        $this->messages[] = $message;
        
        return $this;
    }
    
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
    ): static {
        
        $this->addMessage($this->messageFactory->createMessage(
            level: $level,
            message: $message,
            context: $context,
            key: $key,
            parameters: $parameters,
        ), $log);
        
        return $this;
    }
    
    /**
     * Push messages.
     *
     * @param array<int, mixed>|MessagesInterface $messages
     * @return static $this
     */
    public function push(array|MessagesInterface $messages): static
    {
        if ($messages instanceof MessagesInterface) {
            $messages = $messages->all();
        }
        
        foreach($messages as $message)
        {
            if ($message instanceof MessageInterface) {
                $this->addMessage($message);
            } elseif (is_array($message)) {
                $this->addMessage($this->messageFactory->createMessageFromArray($message));
            }    
        }
    
        return $this;
    }
    
    /**
     * Get the column of the messages.
     *
     * @param string $column The column such as 'message'.
     * @param null|string $index The index such as 'key'.
     * @return array
     */
    public function column(string $column, null|string $index = null): array
    {
        return array_column($this->messages, $column, $index);
    }

    /**
     * Returns a new instance with the filtered messages.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static
    {
        $new = clone $this;
        $new->messages = array_filter($this->messages, $callback);
        return $new;
    }
    
    /**
     * Returns a new instance with the specified key messages filtered.
     *
     * @param string $key
     * @return static
     */
    public function key(string $key): static
    {
        return $this->filter(fn(MessageInterface $m): bool => $m->key() === $key);
    }    
        
    /**
     * Returns a new instance with messages with only the levels specified.
     *
     * @param array $levels
     * @return static
     */
    public function only(array $levels): static
    {
        return $this->filter(fn(MessageInterface $m): bool => in_array($m->level(), $levels));
    }
        
    /**
     * Returns a new instance with messages except the level specified.
     *
     * @param array $levels
     * @return static
     */
    public function except(array $levels): static
    {
        return $this->filter(fn(MessageInterface $m): bool => !in_array($m->level(), $levels));
    }

    /**
     * Get first message.
     *
     * @return null|MessageInterface
     */
    public function first(): null|MessageInterface
    {
        $key = array_key_first($this->messages);
        
        if (is_null($key)) {
            return null;
        }
        
        return $this->messages[$key];    
    }

    /**
     * Get last message.
     *
     * @return null|MessageInterface
     */
    public function last(): null|MessageInterface
    {
        $key = array_key_last($this->messages);
        
        if (is_null($key)) {
            return null;
        }
        
        return $this->messages[$key];    
    }    
    
    /**
     * Returns all messages. 
     *
     * @return array<int, MessageInterface>
     */
    public function all(): array
    {    
        return $this->messages;        
    }
    
    /**
     * Returns true if messages exists, otherwise false.
     *
     * @param null|array $levels The message levels such as ['info', 'debug']
     * @return bool
     */
    public function has(null|array $levels = null): bool
    {
        if ($levels === null) {
            return !empty($this->messages);
        }
        
        foreach($levels as $level)
        {
            if (! $this->filter(fn(MessageInterface $m): bool => $m->level() === $level)->has()) {
                return false;
            }
        }
        
        return true;
    }    
    
    /**
     * Get the iterator. 
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {    
        return new ArrayIterator($this->all());
    }    

    /**
     * Object to array
     *
     * @return array
     */    
    public function toArray(): array
    {
        return (new Collection($this->all()))->toArray();
    }
}