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
 * ModifiersInterface
 */
interface ModifiersInterface
{
    /**
     * Returns the modified message.
     *
     * @param MessageInterface The message to midify.
     * @return MessageInterface The modified message.
     */    
    public function modify(MessageInterface $message): MessageInterface;
    
    /**
     * Add a modifier.
     *
     * @param ModifierInterface $modifier
     * @return static $this
     */    
    public function add(ModifierInterface $modifier): static;

    /**
     * Adds a modifier to the beginning.
     *
     * @param ModifierInterface $modifier
     * @return static $this
     */    
    public function prepend(ModifierInterface $modifier): static;
    
    /**
     * Returns all modifiers.
     *
     * @return array<int, ModifierInterface>
     */    
    public function all(): array;
}