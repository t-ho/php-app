<?php if ($totalPages > 1) : ?>
  <nav>
    <ul>
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
          <li>
            <?php if ($i === (int)$currentPage) : ?>
             <span><?= e($i) ?></span>
            <?php else : ?>
               <a href="?<?= e(buildPageQueryString($_GET, $i)) ?>">
                 <?= e($i) ?>
               </a>
            <?php endif; ?>
          </li>
        <?php endfor; ?>
    </ul>
  </nav>
<?php endif; ?>
