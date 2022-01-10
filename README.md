# Message Service

Messages for PHP applications.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
- [Documentation](#documentation)
	- [Message](#message)
        - [Create Message](#create-message)
        - [Message Factory](#message-factory)
        - [Message Interface](#message-interface)
        - [Render Message](#render-message)
    - [Messages](#messages)
        - [Create Messages](#create-messages)
        - [Messages Factory](#messages-factory)
        - [Add Messages](#add-messages)
        - [Filter Messages](#filter-messages)
        - [Get Messages](#get-messages)
        - [Messages Aware](#messages-aware)
    - [Modifiers](#modifiers)
    - [Modifier](#modifier)
        - [Pluralization](#pluralization)
        - [Parameter Replacer](#parameter-replacer)
        - [Limit Length](#limit-length)
        - [Translator](#translator)
- [Credits](#credits)
___

# Getting started

Add the latest version of the Message service project running this command.

```
composer require tobento/service-message
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

# Documentation

## Message

### Create Message

```php
use Tobento\Service\Message\Message;
use Tobento\Service\Message\MessageInterface;

$message = new Message(
    level: 'success',
    message: 'Hello :name, welcome back',
    context: ['logged_in' => 'John'],
    key: 'user.name', // null|string
    parameters: [':name' => 'John'],
    logged: false,
);

var_dump($message instanceof MessageInterface);
// bool(true)
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **level** | Any level. It's up to you. |
| **message** | The message. |
| **context** | Any context for the message. |
| **key** | A key which might be used for an identifier for input data for instance. |
| **parameters** | Any parameters used for [Modifiers](#modifiers). |
| **logged** | Used as to know if message has been logged already as not to log muliple times. |

### Message Factory

**createMessage**

```php
use Tobento\Service\Message\MessageFactory;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\MessageInterface;

$messageFactory = new MessageFactory();

var_dump($messageFactory instanceof MessageFactoryInterface);
// bool(true)

$message = $messageFactory->createMessage(
    level: 'error',
    message: 'Any error message',
    context: [],
    key: null,
    parameters: [],
    logged: false,
);

var_dump($message instanceof MessageInterface);
// bool(true)
```

**createMessageFromArray**

```php
use Tobento\Service\Message\MessageFactory;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\MessageInterface;

$messageFactory = new MessageFactory();

var_dump($messageFactory instanceof MessageFactoryInterface);
// bool(true)

$message = $messageFactory->createMessageFromArray([
    'level' => 'error',
    'message' => 'Any error message',
    'context' => [],
    'key' => null,
    'parameters' => [],
    'logged' => false,
]);

var_dump($message instanceof MessageInterface);
// bool(true)
```

### Message Interface

The message interface has the following methods:

```php
use Tobento\Service\Message\Message;
use Tobento\Service\Message\MessageInterface;

$message = new Message('error', 'Any error message');

var_dump($message instanceof MessageInterface);
// bool(true)

var_dump($message->level());
// string(5) "error"

var_dump($message->message());
// string(17) "Any error message"

var_dump($message->context());
// array(0) { }

var_dump($message->key());
// NULL or string if a key is set.

var_dump($message->parameters());
// array(0) { }

var_dump($message->parameter('name', 'default'));
// string(7) "default"

var_dump($message->logged());
// bool(false)
```

**With methods**

You may use the with prefixed methods returning a new instance.

```php
use Tobento\Service\Message\Message;
use Tobento\Service\Message\MessageInterface;

$message = new Message('error', 'Any error message');

var_dump($message instanceof MessageInterface);
// bool(true)

$newMessage = $message->withLevel('success');

var_dump($newMessage === $message);
// bool(false)

$newMessage = $message->withMessage('Hello :name, welcome back');

$newMessage = $message->withContext(['logged_in' => 'John']);

$newMessage = $message->withKey('user.name');

$newMessage = $message->withParameters([':name' => 'John']);

$newMessage = $message->withLogged(false);
```

### Render Message

```php
use Tobento\Service\Message\Message;

$message = new Message('error', 'Any error message');

<?= $message ?>
// is escaped

<?= $message->message() ?>
// is NOT escaped
```

## Messages

### Create Messages

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\ModifiersInterface;
use Psr\Log\LoggerInterface;

$messages = new Messages(
    messageFactory: null, // null|MessageFactoryInterface
    modifiers: null, // null|ModifiersInterface
    logger: null, // null|LoggerInterface
);

var_dump($messages instanceof MessagesInterface);
// bool(true)
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **messageFactory** | Used for creating messages. |
| **modifiers** | Used for modifying message. See [Modifiers](#modifiers) for more detail. |
| **logger** | If a logger is set, messages will be logged right after [messages are added](#add-messages) and log parameter is set to true. |

**withMessageFactory**

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessageFactory;
use Tobento\Service\Message\MessageFactoryInterface;

$messages = new Messages();

$newMessages = $messages->withMessageFactory(
    messageFactory: new MessageFactory()
);

var_dump($newMessages->messageFactory() instanceof MessageFactoryInterface);
// bool(true)
```

**withModifiers**

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\ModifiersInterface;

$messages = new Messages();

$newMessages = $messages->withModifiers(
    modifiers: new Modifiers()
);

var_dump($newMessages->modifiers() instanceof ModifiersInterface);
// bool(true)
```

**withLogger**

```php
use Tobento\Service\Message\Messages;
use Psr\Log\LoggerInterface;

$messages = new Messages();

$newMessages = $messages->withLogger(
    logger: null // null|LoggerInterface
);

var_dump($newMessages->logger() instanceof LoggerInterface);
// bool(false) as null
```

### Messages Factory

You might want to use the message factory to create the messages.

```php
use Tobento\Service\Message\MessagesFactory;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\ModifiersInterface;
use Psr\Log\LoggerInterface;

$messagesFactory = new MessagesFactory(
    messageFactory: null, // null|MessageFactoryInterface
    modifiers: null, // null|ModifiersInterface
    logger: null, // null|LoggerInterface
);

var_dump($messagesFactory instanceof MessagesFactoryInterface);
// bool(true)

$messages = $messagesFactory->createMessages();

var_dump($messages instanceof MessagesInterface);
// bool(true)
```

### Add Messages

**By using the addMessage method:**

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\Message;

$messages = new Messages();

$messages->addMessage(
    message: new Message('error', 'Error message'),
    log: false,
);
```

**By using the add method:**

```php
use Tobento\Service\Message\Messages;

$messages = new Messages();

$messages->add(
    level: 'error',
    message: 'Error message',
    context: [],
    key: null, // null|string
    parameters: [],
    log: false,
);
```

**By using the push method:**

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessagesInterface;

$someMessages = new Messages();
$someMessages->add('error', 'Error message');

$messages = new Messages();
$messages->add('success', 'Success message');

$messages->push(
    messages: $someMessages // array|MessagesInterface
);

$messages->push(
    messages: [
        ['level' => 'info', 'message' => 'Some info'],
    ]
);
```

**By using the withMessage method returning a new instance:**

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessageInterface;

$someMessages = new Messages();
$someMessages->add('error', 'Error message');

$messages = new Messages();
$messages->add('success', 'Success message');

$newMessages = $messages->withMessage(
    ...$someMessages // MessageInterface
);
```

### Filter Messages

Filter methods always returning a new instance.

**filter**

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessageInterface;

$messages = new Messages();
$messages->add('success', 'Success message');
$messages->add('error', 'Error message');

$messages = $messages->filter(
    fn(MessageInterface $m): bool => $m->level() === 'error'
);
```

**key**

Filters messages by its key:

```php
use Tobento\Service\Message\Messages;

$messages = new Messages();
$messages->add(level: 'success', message: 'Success message', key: 'foo');
$messages->add('error', 'Error message');

$messages = $messages->key('foo');
```

**only**

Filters messages only with the levels specified:

```php
use Tobento\Service\Message\Messages;

$messages = new Messages();
$messages->add('success', 'Success message');
$messages->add('error', 'Error message');
$messages->add('info', 'Info message');

$messages = $messages->only(levels: ['info', 'success']);
```

**except**

Filters messages except with the levels specified:

```php
use Tobento\Service\Message\Messages;

$messages = new Messages();
$messages->add('success', 'Success message');
$messages->add('error', 'Error message');
$messages->add('info', 'Info message');

$messages = $messages->except(levels: ['info', 'success']);
```

### Get Messages

**all**

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessageInterface;

$messages = new Messages();
$messages->add('success', 'Success message');
$messages->add('error', 'Error message');

foreach($messages->all() as $message) {
    var_dump($message instanceof MessageInterface);
    //bool(true)
}

// or just
foreach($messages as $message) {
    var_dump($message instanceof MessageInterface);
    //bool(true)
}
```

**first**

Get the first message:

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessageInterface;

$messages = new Messages();
$messages->add('success', 'Success message');
$messages->add('error', 'Error message');

var_dump($messages->first() instanceof MessageInterface);
// bool(true)
```

**last**

Get the last message:

```php
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\MessageInterface;

$messages = new Messages();
$messages->add('success', 'Success message');
$messages->add('error', 'Error message');

var_dump($messages->last() instanceof MessageInterface);
// bool(true)
```

**column**

```php
use Tobento\Service\Message\Messages;

$messages = new Messages();
$messages->add('success', 'Success message');
$messages->add('error', 'Error message');

$values = $messages->column(
    column: 'message',
    index: 'key',
);

var_dump($values);
// array(2) { [0]=> string(15) "Success message" [1]=> string(13) "Error message" }
```

**has**

```php
use Tobento\Service\Message\Messages;

$messages = new Messages();
$messages->add('success', 'Success message');
$messages->add('error', 'Error message');

var_dump($messages->has());
// bool(true)

var_dump($messages->has(levels: ['error', 'success']));
// bool(true)

var_dump($messages->has(levels: ['error', 'info']));
// bool(false)
```

### Messages Aware

You might support messages in any class by using the HasMessages trait:

```php
use Tobento\Service\Message\HasMessages;
use Tobento\Service\Message\MessagesAware;
use Tobento\Service\Message\MessagesInterface;

class Foo implements MessagesAware
{
    use HasMessages;
}

$foo = new Foo();

var_dump($foo->messages() instanceof MessagesInterface);
// bool(true)
```

## Modifiers

Modifiers can be used for modifying the message such as translating.

**Create Modifiers**

```php
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\ModifiersInterface;
use Tobento\Service\Message\Modifier;

$modifiers = new Modifiers(
    new Modifier\ParameterReplacer(),
);

var_dump($modifiers instanceof ModifiersInterface);
// bool(true)
```

**Add Modifier**

```php
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\Modifier;

$modifiers = new Modifiers();

$modifiers->add(new Modifier\ParameterReplacer());
```

**Prepend Modifier**

Adds a modifier to the beginning.

```php
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\Modifier;

$modifiers = new Modifiers();

$modifiers->add(new Modifier\ParameterReplacer());

$modifiers->prepend(new Modifier\Pluralization());
```

**Modify Message**

```php
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\Modifier;
use Tobento\Service\Message\Message;

$modifiers = new Modifiers(
    new Modifier\ParameterReplacer(),
);

$message = new Message(
    level: 'success',
    message: 'Hello :name, welcome back',
    parameters: [':name' => 'John'],
);

$newMessage = $modifiers->modify($message);

var_dump($newMessage->message());
// string(24) "Hello John, welcome back"

var_dump($newMessage === $message);
// bool(false)
```

**Get Modifiers**

```php
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\Modifier;

$modifiers = new Modifiers(
    new Modifier\ParameterReplacer(),
);

foreach($modifiers->all() as $modifier) {
    var_dump($modifier instanceof ModifierInterface);
    // bool(true)
}
```

## Modifier

### Pluralization

```php
use Tobento\Service\Message\Modifier\Pluralization;
use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\Message;

$modifier = new Pluralization(key: 'count');

var_dump($modifier instanceof ModifierInterface);
// bool(true)

$message = new Message(
    level: 'success',
    message: 'One item created|Many items created',
    parameters: ['count' => 5],
);

$newMessage = $modifier->modify($message);

var_dump($newMessage->message());
// string(18) "Many items created"

var_dump($newMessage === $message);
// bool(false)
```

### Parameter Replacer

```php
use Tobento\Service\Message\Modifier\ParameterReplacer;
use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\Message;

$modifier = new ParameterReplacer();

var_dump($modifier instanceof ModifierInterface);
// bool(true)

$message = new Message(
    level: 'success',
    message: 'Hello :name, welcome back',
    parameters: [':name' => 'John'],
);

$newMessage = $modifier->modify($message);

var_dump($newMessage->message());
// string(24) "Hello John, welcome back"

var_dump($newMessage === $message);
// bool(false)
```

### Limit Length

```php
use Tobento\Service\Message\Modifier\LimitLength;
use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\Message;

$modifier = new LimitLength(
    length: 15,
    parameterKey: 'limit_length',
);

var_dump($modifier instanceof ModifierInterface);
// bool(true)

$message = new Message(
    level: 'success',
    message: 'Some very long message',
    parameters: [
        // used instead of default if set.
        'limit_length' => 10,
    ],
);

$newMessage = $modifier->modify($message);

var_dump($newMessage->message());
// string(10) "Some ve..."

var_dump($newMessage === $message);
// bool(false)
```

### Translator

For more information about the translator, check out the [Translation Service](https://github.com/tobento-ch/service-translation) documentation.

```php
use Tobento\Service\Translation;
use Tobento\Service\Message\Modifier\Translator;
use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\Message;

$translator = new Translation\Translator(
    new Translation\Resources(
        new Translation\Resource('*', 'de', [
            'Some error occured' => 'Some error occured',
        ]),
        new Translation\Resource('*', 'de', [
            'Some error occured' => 'Ein Fehler is passiert',
        ]),
    ),
    new Translation\Modifiers(
        new Translation\Modifier\Pluralization(),        
        new Translation\Modifier\ParameterReplacer(),
    ),
    new Translation\MissingTranslationHandler(),
    'de',
);

$modifier = new Translator(
    translator: $translator,
    src: '*',
);

var_dump($modifier instanceof ModifierInterface);
// bool(true)

$message = new Message(
    level: 'error',
    message: 'Some error occured',
);

$newMessage = $modifier->modify($message);

var_dump($newMessage->message());
// string(22) "Ein Fehler is passiert"

var_dump($newMessage === $message);
// bool(false)
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)