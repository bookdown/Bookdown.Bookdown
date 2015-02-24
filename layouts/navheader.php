<?php use Aura\Html\Escaper as e; ?>
<nav class="navheader">
    <table width="100%">
        <tr>
            <th colspan="3" align="center"><?= e::h(
                $this->page->getNumberAndTitle()
            ); ?></th>
        </tr>
        <tr>
            <td width="20%" align="left"><?php
                $prev = $this->page->getPrev();
                if ($prev) {
                    echo $this->anchor(
                        $prev->getAbsoluteHref(),
                        $prev->getTitle()
                    );
                }
            ?></td>
            <td width="60%" align="center"><?php
                $parent = $this->page->getParent();
                if ($parent) {
                    echo e::h($parent->getNumberAndTitle());
                }
            ?></th>
            <td width="20%" align="right"><?php
                $next = $this->page->getNext();
                if ($next) {
                    echo $this->anchor(
                        $next->getAbsoluteHref(),
                        $next->getTitle()
                    );
                }
            ?></td>
        </tr>
    </table>
</nav>
