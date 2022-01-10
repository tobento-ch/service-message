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
 * Has messages trait.
 */
trait HasMessages
{
    /**
     * @var null|MessagesInterface
     */
    protected null|MessagesInterface $messages = null;
        
    /**
     * Returns the messages.
     *
     * @return MessagesInterface
     */    
    public function messages(): MessagesInterface
    {
        if ($this->messages === null) {
            return $this->messages = new Messages();
        }
        
        return $this->messages;
    }
}