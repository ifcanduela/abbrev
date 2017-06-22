<?php

namespace ifcanduela\abbrev;

/**
 * Find unambiguous abbreviations for all words in a list.
 *
 * Suggestions and matches are case-insensitive.
 *
 * ```php
 * $abbrev = new Abbrev(['ape', 'aperture', 'apprentice'], 'albino', 'append');
 * $input = 'ap';
 *
 * $match = $abbrev->match($input);
 *
 * if ($match === false) {
 *     var_dump($abbrev->suggest($input));
 * }
 *
 * var_dump($abbrev->abbreviate());
 * ```
 */
class Abbrev
{
    /** @var boolean */
    private $abbreviated = false;
    /** @var string[] */
    private $abbreviations = [];
    /** @var string[] */
    private $words = [];

    /**
     * Create a word list.
     *
     * As many arguments as desired can be passed, as strings or arrays of strings.
     *
     * @param array|string $words
     */
    public function __construct(...$words)
    {
        $this->words = array_unique($this->flattenArgs($words));
        sort($this->words, SORT_FLAG_CASE | SORT_NATURAL);
    }

    /**
     * Return a single matching word, if it exists.
     *
     * @param string $word
     * @return string|false
     */
    public function match(string $word)
    {
        $this->processAbbreviations();
        $word = strtolower($word);

        if ($this->abbreviations && isset($this->abbreviations[$word])) {
            return $this->abbreviations[$word];
        }

        return false;
    }

    /**
     * Obtain a list of possible word matches.
     *
     * @param string $word
     * @return string[]
     */
    public function suggest(string $word)
    {
        $this->processAbbreviations();
        $word = strtolower($word);
        $suggestions = [];

        if ($this->abbreviations) {
            $len = mb_strlen($word);

            foreach ($this->words as $w) {
                if (substr($w, 0, $len) === $word) {
                    $suggestions[] = $w;
                }
            }
        }

        return $suggestions;
    }

    /**
     * Create a list of unambiguous strings for the word list.
     *
     * @return string[]
     */
    public function abbreviate()
    {
        $this->processAbbreviations();

        return $this->abbreviations;
    }

    protected function processAbbreviations()
    {
        if ($this->abbreviated) {
            return $this->abbreviations;
        }

        $abbrevs = [];
        $words = $this->words;
        $word_count = count($words);

        for ($i = 0; $i < $word_count; $i++) {
            $current = strtolower($words[$i]);
            $prev = strtolower($words[$i - 1] ?? "");
            $next = strtolower($words[$i + 1] ?? "");

            $current_length = mb_strlen($current);

            for ($j = 0; $j <= $current_length; $j++) {
                $current_substring = mb_substr($current, 0, $j);

                $prev_substring = mb_substr($prev, 0, $j);
                $next_substring = mb_substr($next, 0, $j);

                $prev_match = $current_substring === $prev_substring;
                $next_match = $current_substring === $next_substring;
                $other_match = $prev_match || $next_match;
                $self_match = $current === $current_substring;

                if (!$other_match || $self_match) {
                    $abbrevs[$current_substring] = $words[$i];
                }
            }
        }

        $this->abbreviated = true;

        return $this->abbreviations = $abbrevs;
    }

    protected function flattenArgs($values)
    {
        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($values));

        return iterator_to_array($it, false);
    }
}
