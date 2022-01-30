<?php
/**
 *
 * This file is part of Bookdown for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Bookdown\Bookdown\Content;

/**
 *
 * Represents the tocHeadingIterator on a page.
 *
 * @package bookdown/bookdown
 *
 */
class TocHeadingIterator implements \Iterator, \Countable
{
    /**
     * @var TocHeading[]
     */
    protected $sourceHeadings;

    /**
     * @var TocHeading[]
     */
    protected $rootHeadings;

    /**
     * @var int
     */
    protected $current;

    /**
     * TocHeadingIterator constructor.
     * @param Heading[] $headings
     */
    public function __construct(array $headings)
    {
        $this->sourceHeadings = $this->createSourceHeadingList($headings);
        $this->rootHeadings = $this->findRoots();
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        $heading = $this->rootHeadings[$this->current];
        return $this->decorateHeading($heading, $this->findChildren($heading));
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        ++$this->current;
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->current;
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        return array_key_exists($this->current, $this->rootHeadings);
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->current = 1;
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->rootHeadings);
    }

    /**
     * @param Heading[] $headings
     * @return TocHeading[]
     */
    protected function createSourceHeadingList(array $headings)
    {
        $result = array();

        foreach ($headings as $heading) {
             $result[trim($heading->getNumber(), '.')] = $heading;
        }

        return $result;
    }

    /**
     * @return TocHeading[]
     */
    protected function findRoots()
    {
        $headings = $this->sourceHeadings;

        $firstHeading = reset($headings);
        $level = $firstHeading->getLevel();

        $roots = array_filter($headings, function ($key) use ($level) {
            if (count(explode('.', $key)) === $level) {
                return true;
            }

            return false;
        }, ARRAY_FILTER_USE_KEY);

        return $this->normalizeRootKeys($roots, $level);
    }

    /**
     * @param TocHeading[] $roots
     * @param string $level
     * @return TocHeading[]
     */
    protected function normalizeRootKeys(array $roots, $level)
    {
        $result = array();

        foreach ($roots as $key => $root) {
            $explodedKey = explode('.', $key);
            $normalizedKey = $explodedKey[$level - 1];

            $result[$normalizedKey] = $root;
        }

        return $result;
    }

    /**
     * @param TocHeading $rootHeading
     * @return TocHeading[]
     */
    protected function findChildren(TocHeading $rootHeading)
    {
        $headings = $this->sourceHeadings;

        $number = (string)$rootHeading->getNumber();

        $children = array_filter($headings, function ($key) use ($number) {
            if (strpos((string)$key, $number) === 0) {
                return true;
            }

            return false;

        }, ARRAY_FILTER_USE_KEY);

        return $this->normalizeChildKeys($children, $number);
    }

    /**
     * @param TocHeading[] $headings
     * @param $number
     * @return TocHeading[]
     */
    protected function normalizeChildKeys(array $headings, $number)
    {
        $result = array();

        foreach ($headings as $key => $heading) {
            $normalizedKey = substr($key, strlen($number));

            $result[$this->trimNumber($normalizedKey)] = $heading;
        }

        return $result;
    }

    /**
     * @param TocHeading $heading
     * @param TocHeading[] $children
     * @return Heading
     */
    protected function decorateHeading(TocHeading $heading, $children)
    {
        if (count($children) === 0) {
            return $heading;
        }

        $heading->setChildren(new TocHeadingIterator($children));
        return $heading;
    }

    /**
     * @param string $number
     * @return string
     */
    protected function trimNumber($number)
    {
        return trim($number, '.');
    }

}