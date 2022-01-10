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
 * ModifierInterface
 */
interface ModifierInterface
{
    /**
     * Returns the modified message.
     *
     * @param MessageInterface The message to midify.
     * @return MessageInterface The modified message.
     */    
    public function modify(MessageInterface $message): MessageInterface;
}