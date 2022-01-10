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
 * MessagesFactoryInterface
 */
interface MessagesFactoryInterface
{
    /**
     * Create a new Messages.
     *
     * @return MessagesInterface
     */
    public function createMessages(): MessagesInterface;
}