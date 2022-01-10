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

use Tobento\Service\Message\Modifier\ParameterReplacer;
use Tobento\Service\Message\Modifier\Pluralization;
use Psr\Log\LoggerInterface;

/**
 * MessagesFactory
 */
class MessagesFactory implements MessagesFactoryInterface
{
    /**
     * Create a new MessagesFactory.
     *
     * @param null|MessageFactoryInterface $messageFactory
     * @param null|ModifiersInterface $modifiers
     * @param null|LoggerInterface $logger
     */    
    public function __construct(
        protected null|MessageFactoryInterface $messageFactory = null,
        protected null|ModifiersInterface $modifiers = null,
        protected null|LoggerInterface $logger = null,
    ) {
        $this->messageFactory = $messageFactory ?: new MessageFactory();
        
        if (is_null($modifiers)) {
            $this->modifiers = new Modifiers(
                new Pluralization(),
                new ParameterReplacer(),
            );
        }
    }
    
    /**
     * Create a new Messages.
     *
     * @return MessagesInterface
     */
    public function createMessages(): MessagesInterface
    {
        return new Messages(
            $this->messageFactory,
            $this->modifiers,
            $this->logger,
        );
    }
}