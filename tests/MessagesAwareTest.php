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

namespace Tobento\Service\Message\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Message\MessagesAware;
use Tobento\Service\Message\HasMessages;
use Tobento\Service\Message\MessagesInterface;

/**
 * MessagesAwareTest
 */
class MessagesAwareTest extends TestCase
{   
    public function testMessagesMethod()
    {
        $class = new class() implements MessagesAware
        {
            use HasMessages;
        };
        
        $this->assertInstanceOf(
            MessagesInterface::class,
            (new $class())->messages()
        );
    }
}