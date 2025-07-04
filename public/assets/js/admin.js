document.addEventListener('DOMContentLoaded', function() {
  // Initialize TinyMCE for all textareas with js-tinymce class
  const tinymceTextareas = document.querySelectorAll('.js-tinymce');
  
  if (tinymceTextareas.length > 0) {
    // Load TinyMCE script if not already loaded
    if (typeof tinymce === 'undefined') {
      const script = document.createElement('script');
      script.src = 'https://cdn.tiny.cloud/1/bzqo8ikmoao90q0x24dyj546pjt7vna6d90oioqye2dy94p3/tinymce/6/tinymce.min.js';
      script.referrerPolicy = 'origin';
      script.onload = initializeTinyMCE;
      document.head.appendChild(script);
    } else {
      initializeTinyMCE();
    }
  }
  
  function initializeTinyMCE() {
    tinymceTextareas.forEach(textarea => {
      tinymce.init({
        target: textarea,
        height: parseInt(textarea.dataset.height) || 400,
        menubar: false,
        plugins: [
          'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
          'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
          'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
          'bold italic forecolor | alignleft aligncenter ' +
          'alignright alignjustify | bullist numlist outdent indent | ' +
          'removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
      });
    });
  }
});