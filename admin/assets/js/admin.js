/* Hasnet Admin – JS */
(function () {
  'use strict';

  // ── Sidebar toggle (mobile) ──────────────────────────────
  const sidebar  = document.getElementById('adminSidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  const toggleBtn = document.getElementById('sidebarToggle');

  function openSidebar() {
    sidebar?.classList.add('open');
    overlay?.classList.add('show');
  }
  function closeSidebar() {
    sidebar?.classList.remove('open');
    overlay?.classList.remove('show');
  }

  toggleBtn?.addEventListener('click', openSidebar);
  overlay?.addEventListener('click', closeSidebar);

  // ── User dropdown ────────────────────────────────────────
  const userMenuBtn = document.getElementById('userMenuBtn');
  const userMenu    = document.getElementById('userMenu');

  userMenuBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    userMenu?.classList.toggle('show');
  });
  document.addEventListener('click', () => userMenu?.classList.remove('show'));

  // ── Image preview for file inputs ───────────────────────
  document.querySelectorAll('[data-preview]').forEach(input => {
    input.addEventListener('change', function () {
      const target = document.getElementById(this.dataset.preview);
      if (!target || !this.files[0]) return;
      const reader = new FileReader();
      reader.onload = e => {
        target.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
      };
      reader.readAsDataURL(this.files[0]);
    });
  });

  // ── Confirm delete ───────────────────────────────────────
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function (e) {
      if (!confirm(this.dataset.confirm || 'Are you sure?')) e.preventDefault();
    });
  });

  // ── Dismiss alerts ───────────────────────────────────────
  document.querySelectorAll('.alert [data-dismiss]').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.alert')?.remove());
  });

  // ── Rich text editors (Quill) ────────────────────────────
  function initQuillEditors() {
    if (typeof Quill === 'undefined') {
      setTimeout(initQuillEditors, 100);
      return;
    }
    document.querySelectorAll('[data-quill]').forEach(el => {
      if (el.__quill) return; // already initialized
      const hiddenId = el.dataset.quill;
      const hidden   = document.getElementById(hiddenId);
      if (!hidden) return;

      const quill = new Quill(el, {
        theme: 'snow',
        placeholder: 'Write your content here...',
        modules: {
          toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            [{ align: [] }],
            ['clean']
          ]
        }
      });

      el.__quill = quill;

      // restore saved content
      if (hidden.value && hidden.value.trim()) {
        quill.root.innerHTML = hidden.value;
      }

      // sync to hidden on every change
      quill.on('text-change', () => {
        hidden.value = quill.root.innerHTML;
      });

      const form = hidden.closest('form');
      form?.addEventListener('submit', () => {
        hidden.value = quill.root.innerHTML;
      });
    });
  }
  initQuillEditors();

  // ── Slug auto-generation from title ─────────────────────
  const titleInput = document.getElementById('input-title');
  const slugInput  = document.getElementById('input-slug');
  let slugEdited = false;

  if (slugInput?.value) slugEdited = true;

  titleInput?.addEventListener('input', function () {
    if (slugEdited) return;
    slugInput.value = this.value
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/[\s]+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '');
  });
  slugInput?.addEventListener('input', () => { slugEdited = true; });

  // ── Copy to clipboard ────────────────────────────────────
  document.querySelectorAll('[data-copy]').forEach(btn => {
    btn.addEventListener('click', function () {
      const target = document.getElementById(this.dataset.copy);
      if (!target) return;
      const text = target.tagName === 'INPUT' || target.tagName === 'TEXTAREA'
        ? target.value
        : target.innerText;
      navigator.clipboard.writeText(text).then(() => {
        const orig = this.innerHTML;
        this.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
        setTimeout(() => { this.innerHTML = orig; }, 2000);
      });
    });
  });

  // ── Color sync (text <-> picker) ─────────────────────────
  document.querySelectorAll('[data-color-text]').forEach(picker => {
    const textId = picker.dataset.colorText;
    const text   = document.getElementById(textId);
    if (!text) return;
    picker.addEventListener('input', () => { text.value = picker.value; });
    text.addEventListener('input',  () => { picker.value = text.value; });
  });

})();
