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
 * Modifiers
 */
class Modifiers implements ModifiersInterface
{
    /**
     * @var array<int, ModifierInterface>
     */
    protected array $modifiers = [];
    
    /**
     * Create a new Modifiers.
     *
     * @param ModifierInterface $modifier
     */    
    public function __construct(
        ModifierInterface ...$modifier,
    ) {
        $this->modifiers = $modifier;
    }

    /**
     * Returns the modified message.
     *
     * @param MessageInterface The message to midify.
     * @return MessageInterface The modified message.
     */    
    public function modify(MessageInterface $message): MessageInterface
    {
        foreach($this->all() as $modifier)
        {
            $message = $modifier->modify($message);
        }
        
        return $message;
    }
    
    /**
     * Add a modifier.
     *
     * @param ModifierInterface $modifier
     * @return static $this
     */    
    public function add(ModifierInterface $modifier): static
    {
        $this->modifiers[] = $modifier;
        return $this;
    }    

    /**
     * Adds a modifier to the beginning.
     *
     * @param ModifierInterface $modifier
     * @return static $this
     */    
    public function prepend(ModifierInterface $modifier): static
    {
        array_unshift($this->modifiers, $modifier);
        return $this;
    }
    
    /**
     * Returns all modifiers.
     *
     * @return array<int, ModifierInterface>
     */    
    public function all(): array
    {
        return $this->modifiers;
    }    
}