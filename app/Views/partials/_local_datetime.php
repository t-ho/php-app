<?php
/**
 * Local DateTime Partial
 *
 * Displays datetime in user's local timezone using JavaScript
 * Format: Jul 06, 2025 @ 8:20PM
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

<span class="local-datetime js-local-datetime <?= e($class) ?>" 
      data-utc="<?= e($datetime) ?>"<?= $attributeString ?>>
    <?= strtotime($datetime) ?>
</span>
