<?php
use Aura\Html\Escaper as e;

$prev = $this->page->getPrev();
$parent = $this->page->getParent();
$next = $this->page->getNext();
?>
<nav class="navfooter">
    <table width="100%">
        <tr>
            <td width="40%" align="left"><?php if ($prev) {
                echo $this->anchor(
                    $prev->getAbsoluteHref(), 'Prev'
                );
            } ?></td>
            <td width="20%" align="center"><?php if ($parent) {
                echo $this->anchor(
                    $parent->getAbsoluteHref(), 'Up'
                );
            } ?></td>
            <td width="40%" align="right"><?php if ($next) {
                echo $this->anchor(
                    $next->getAbsoluteHref(), 'Next'
                );
            } ?></td>
        </tr>
        <tr>
            <td width="40%" align="left" valign="top"><?php if ($prev) {
                echo e::h($prev->getNumberAndTitle());
            } ?></td>
            <td width="20%" align="center" valign="top"><?php if ($parent) {
                echo e::h($parent->getNumberAndTitle());
            } ?></td>
            <td width="40%" align="right" valign="top"><?php if ($next) {
                echo e::h($next->getNumberAndTitle());
            } ?></td>
        </tr>
    </table>
</nav>
