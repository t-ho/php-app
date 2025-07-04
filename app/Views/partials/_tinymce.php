<textarea 
  id="<?= $id ?>" 
  name="<?= $name ?>" 
  class="form-control js-tinymce"
  rows="<?= $rows ?? 12 ?>"
  data-height="<?= $height ?? 400 ?>"
  placeholder="<?= $placeholder ?? '' ?>"
  <?= $required ? 'required' : '' ?>
><?= $value ?? '' ?></textarea>