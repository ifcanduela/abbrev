# Abbrev

Find unambiguous abbreviations for all words in a list.

Just like [npm's abbrev](https://www.npmjs.com/package/abbrev),
which is just like [ruby's Abbrev](http://apidock.com/ruby/Abbrev).

## Installation

Use composer:

```
composer install ifcanduela/abbrev
```

## Usage

Feed a word list to the `Abbrev` constructor and then call one of the three public methods.

Suggestions and matches are case-insensitive. The constructor accepts any number of strings or
arrays of strings, arbitrarily nested. Once you have an instance it's ready to go.

```php
use ifcanduela\abbrev\Abbrev;

$abbrev = new Abbrev(
        'foo',
        'bar',
        ['baz', 'foobar', ['barbaz']],
    );
```

### Abbrev::match($word)

Get a matching word from the list, or `false` if the input is ambiguous.

```php
$abbrev = new Abbrev(['ape', 'aperture', 'apprentice'], 'albino', 'append');

$match = $abbrev->match('ap'); // there is not unambiguous match
// => false

$match = $abbrev->match('al'); // there is only one possible match
// => "albino"
```

### Abbrev::suggest($word)

Get a list of matching words from the list for an ambiguous or unambiguous input.

```php
$abbrev = new Abbrev(['ape', 'aperture', 'apprentice'], 'albino', 'append');

$suggestions = $abbrev->suggest('app');
// append
// apprentice
```

### Abbrev::abbreviate()

Retrieve a list of all possible abbreviations.

```php
$abbrev = new Abbrev(['ape', 'aperture', 'apprentice'], 'albino', 'append');

$suggestions = $abbrev->abbreviate();
// [
//   'alb'        => 'albino',
//   'albi'       => 'albino',
//   'albin'      => 'albino',
//   'albino'     => 'albino',
//   'ape'        => 'ape',
//   'aper'       => 'aperture',
//   'apert'      => 'aperture',
//   'apertu'     => 'aperture',
//   'apertur'    => 'aperture',
//   'aperture'   => 'aperture',
//   'appe'       => 'append',
//   'appen'      => 'append',
//   'append'     => 'append',
//   'appr'       => 'apprentice',
//   'appre'      => 'apprentice',
//   'appren'     => 'apprentice',
//   'apprent'    => 'apprentice',
//   'apprenti'   => 'apprentice',
//   'apprentic'  => 'apprentice',
//   'apprentice' => 'apprentice',
// ]
```
