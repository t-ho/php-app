<?php if ($totalPages > 1) : ?>
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <!-- Previous button -->
      <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
        <?php if ($currentPage <= 1) : ?>
          <span class="page-link">Previous</span>
        <?php else : ?>
          <a class="page-link" href="?<?= e(buildPageQueryString($_GET, $currentPage - 1)) ?>">Previous</a>
        <?php endif; ?>
      </li>

      <!-- First page -->
      <?php if ($currentPage > 3) : ?>
        <li class="page-item">
          <a class="page-link" href="?<?= e(buildPageQueryString($_GET, 1)) ?>">1</a>
        </li>
        <?php if ($currentPage > 4) : ?>
          <li class="page-item disabled">
            <span class="page-link">...</span>
          </li>
        <?php endif; ?>
      <?php endif; ?>

      <!-- Pages around current page -->
      <?php
      $start = max(1, $currentPage - 2);
      $end = min($totalPages, $currentPage + 2);
      for ($i = $start; $i <= $end; $i++) : ?>
        <li class="page-item <?= $i === (int)$currentPage ? 'active' : '' ?>">
          <?php if ($i === (int)$currentPage) : ?>
            <span class="page-link"><?= e($i) ?></span>
          <?php else : ?>
            <a class="page-link" href="?<?= e(buildPageQueryString($_GET, $i)) ?>">
              <?= e($i) ?>
            </a>
          <?php endif; ?>
        </li>
      <?php endfor; ?>

      <!-- Last page -->
      <?php if ($currentPage < $totalPages - 2) : ?>
        <?php if ($currentPage < $totalPages - 3) : ?>
          <li class="page-item disabled">
            <span class="page-link">...</span>
          </li>
        <?php endif; ?>
        <li class="page-item">
          <a class="page-link" href="?<?= e(buildPageQueryString($_GET, $totalPages)) ?>"><?= e($totalPages) ?></a>
        </li>
      <?php endif; ?>

      <!-- Next button -->
      <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
        <?php if ($currentPage >= $totalPages) : ?>
          <span class="page-link">Next</span>
        <?php else : ?>
          <a class="page-link" href="?<?= e(buildPageQueryString($_GET, $currentPage + 1)) ?>">Next</a>
        <?php endif; ?>
      </li>
    </ul>
  </nav>
<?php endif; ?>