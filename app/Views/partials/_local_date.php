<?php
/**
 * Local Date Partial
 *
 * Displays date in user's local timezone using JavaScript
 * Format: Jul 08, 2025
 *
 * @param string $datetime - The datetime string (any format that new Date() can parse)
 * @param string $class - Additional CSS classes (optional)
 * @param array $attributes - Additional HTML attributes (optional)
 */

$class = $class ?? '';
$attributes = $attributes ?? [];

// Build attributes string
$attributeString = '';
foreach ($attributes as $key => $value) {
    $attributeString .= ' ' . e($key) . '="' . e($value) . '"';
}
?>

<span class="local-date js-local-date <?= e($class) ?>" 
      data-utc="<?= e($datetime) ?>"<?= $attributeString ?>>
    <?= strtotime($datetime) ?>
</span>
