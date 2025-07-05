<textarea 
  id="<?= $id ?>" 
  name="<?= $name ?>" 
  class="form-control js-tinymce"
  rows="<?= $rows ?? 12 ?>"
  data-height="<?= $height ?? 400 ?>"
  data-required="<?= $required ? 'true' : 'false' ?>"
  placeholder="<?= $placeholder ?? '' ?>"
><?= $value ?? '' ?></textarea>