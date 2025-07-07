import { DateFormatter } from './modules/date-formatter.js';
import { TinyMCEEditor } from './modules/tinymce-editor.js';

document.addEventListener('DOMContentLoaded', async () => {
  const dateFormatter = new DateFormatter();
  dateFormatter.init();

  const editor = new TinyMCEEditor();
  await editor.init();
});