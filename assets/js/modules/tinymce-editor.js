export class TinyMCEEditor {
  constructor() {
    this.initialized = false;
  }

  async loadTinyMCE() {
    return new Promise((resolve, reject) => {
      if (typeof window.tinymce !== "undefined") {
        resolve(window.tinymce);
        return;
      }

      const script = document.createElement("script");
      script.src =
        "https://cdn.tiny.cloud/1/bzqo8ikmoao90q0x24dyj546pjt7vna6d90oioqye2dy94p3/tinymce/6/tinymce.min.js";
      script.referrerPolicy = "origin";

      // Get nonce from existing script tag for CSP compliance
      const existingScript = document.querySelector("script[nonce]");
      if (existingScript) {
        script.nonce = existingScript.nonce;
      }

      script.onload = () => resolve(window.tinymce);
      script.onerror = reject;
      document.head.appendChild(script);
    });
  }

  async init() {
    if (this.initialized) return;

    const tinymceTextareas = document.querySelectorAll(".js-tinymce");
    if (tinymceTextareas.length === 0) return;

    await this.loadTinyMCE();
    this.initialized = true;

    tinymceTextareas.forEach((textarea) => {
      window.tinymce.init({
        target: textarea,
        height: parseInt(textarea.dataset.height) || 400,
        menubar: false,
        plugins: [
          "advlist",
          "autolink",
          "lists",
          "link",
          "image",
          "charmap",
          "preview",
          "anchor",
          "searchreplace",
          "visualblocks",
          "code",
          "fullscreen",
          "insertdatetime",
          "media",
          "table",
          "help",
          "wordcount",
        ],
        toolbar:
          "undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help",
        content_style:
          "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
        images_upload_url: "/admin/upload-image",
        images_upload_credentials: true,
        images_upload_handler: this.handleImageUpload.bind(this),
        image_advtab: true,
        image_uploadtab: true,
        file_picker_types: "image",
        paste_data_images: true,
      });
    });

    this.setupFormValidation();
  }

  handleImageUpload(blobInfo) {
    return new Promise((resolve, reject) => {
      const formData = new FormData();
      formData.append("file", blobInfo.blob(), blobInfo.filename());

      const csrfInput = document.querySelector('input[name="csrf_token"]');
      if (csrfInput) {
        formData.append("csrf_token", csrfInput.value);
      }

      fetch("/admin/upload-image", {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
          }
          return response.json();
        })
        .then((result) => {
          if (result.location) {
            resolve(result.location);
          } else {
            reject(result.error || "Upload failed");
          }
        })
        .catch((error) => {
          reject("Upload failed: " + error.message);
        });
    });
  }

  setupFormValidation() {
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
      form.addEventListener("submit", (e) => {
        let isValid = true;
        const requiredTinyMCEFields = form.querySelectorAll(
          'textarea[data-required="true"].js-tinymce',
        );

        requiredTinyMCEFields.forEach((textarea) => {
          const editorId = textarea.id;
          const editor = window.tinymce.get(editorId);

          if (editor) {
            const textContent = editor.getContent({ format: "text" }).trim();
            const htmlContent = editor.getContent();
            const hasImages = htmlContent.includes("<img");

            if (!textContent && !hasImages) {
              isValid = false;
              editor.focus();
              let errorDiv = form.querySelector(`#${editorId}-error`);
              if (!errorDiv) {
                errorDiv = document.createElement("div");
                errorDiv.id = `${editorId}-error`;
                errorDiv.className = "text-danger mt-1";
                textarea.parentNode.appendChild(errorDiv);
              }
              errorDiv.textContent = "This field is required.";
            } else {
              const errorDiv = form.querySelector(`#${editorId}-error`);
              if (errorDiv) {
                errorDiv.remove();
              }
            }
          }
        });

        if (!isValid) {
          e.preventDefault();
        }
      });
    });
  }
}

