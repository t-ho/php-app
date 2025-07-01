<?php
function buildQueryString(array $params, int $page): string
{
    $params['page'] = $page;
    return http_build_query($params);
}

$queryParam = $_GET;
unset($queryParam['page']);

?>
<?php if ($totalPages > 1) : ?>
  <nav>
    <ul>
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
          <li>
            <?php if ($i === (int)$currentPage) : ?>
             <span><?= htmlspecialchars($i) ?></span>
            <?php else : ?>
               <a href="?<?= htmlspecialchars(buildQueryString($queryParam, $i)) ?>">
                 <?= htmlspecialchars($i) ?>
               </a>
            <?php endif; ?>
          </li>
        <?php endfor; ?>
    </ul>
  </nav>
<?php endif; ?>
