/**
 * Admin Translations Page JS
 */
(function () {
  'use strict';

  const { ajaxUrl, nonce } = window.gwTranslations || {};
  if (!ajaxUrl) return;

  const searchInput = document.getElementById('gw-search');
  const sectionFilter = document.getElementById('gw-section-filter');
  const saveAllBtn = document.getElementById('gw-save-all');
  const saveStatus = document.getElementById('gw-save-status');
  const table = document.getElementById('gw-translations-table');

  if (!table) return;

  const allRows = table.querySelectorAll('.gw-translation-row');
  const sectionHeaders = table.querySelectorAll('.gw-section-header');
  const allInputs = table.querySelectorAll('.gw-translation-input');
  const dirty = new Set();

  // Auto-resize textareas
  function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.max(32, el.scrollHeight) + 'px';
  }

  allInputs.forEach(input => {
    autoResize(input);

    input.addEventListener('input', () => {
      autoResize(input);
      const original = input.dataset.original || '';
      const current = input.value;
      const id = input.dataset.lang + '::' + input.dataset.key;

      if (current !== original) {
        input.classList.add('is-dirty');
        dirty.add(id);
      } else {
        input.classList.remove('is-dirty');
        dirty.delete(id);
      }

      // Update empty cell styling
      const td = input.closest('.gw-col-lang');
      if (td) {
        td.classList.toggle('gw-empty', current === '');
      }

      updateSaveBtn();
    });
  });

  function updateSaveBtn() {
    if (dirty.size > 0) {
      saveAllBtn.textContent = `Save ${dirty.size} Change${dirty.size > 1 ? 's' : ''}`;
      saveAllBtn.disabled = false;
    } else {
      saveAllBtn.textContent = 'Save All Changes';
      saveAllBtn.disabled = false;
    }
  }

  // Search
  if (searchInput) {
    searchInput.addEventListener('input', debounce(applyFilters, 200));
  }

  // Section filter
  if (sectionFilter) {
    sectionFilter.addEventListener('change', applyFilters);
  }

  function applyFilters() {
    const query = (searchInput?.value || '').toLowerCase();
    const section = sectionFilter?.value || '';

    const visibleSections = new Set();

    allRows.forEach(row => {
      const rowSection = row.dataset.section || '';
      const rowKey = row.dataset.key || '';
      const rowTexts = Array.from(row.querySelectorAll('.gw-translation-input'))
        .map(i => i.value.toLowerCase());
      rowTexts.push(rowKey.toLowerCase());

      const matchesSection = !section || rowSection === section;
      const matchesSearch = !query || rowTexts.some(t => t.includes(query));

      const visible = matchesSection && matchesSearch;
      row.classList.toggle('is-hidden', !visible);
      if (visible) {
        visibleSections.add(rowSection);
      }
    });

    // Show/hide section headers
    sectionHeaders.forEach(header => {
      const headerSection = header.dataset.section || '';
      const matchesSection = !section || headerSection === section;
      const hasVisibleRows = visibleSections.has(headerSection);
      header.classList.toggle('is-hidden', !matchesSection || !hasVisibleRows);
    });
  }

  // Save all
  if (saveAllBtn) {
    saveAllBtn.addEventListener('click', async () => {
      if (dirty.size === 0) {
        showStatus('No changes to save');
        return;
      }

      saveAllBtn.disabled = true;
      saveAllBtn.textContent = 'Saving...';

      // Group changes by language
      const byLang = {};
      allInputs.forEach(input => {
        const id = input.dataset.lang + '::' + input.dataset.key;
        if (dirty.has(id)) {
          const lang = input.dataset.lang;
          if (!byLang[lang]) byLang[lang] = {};
          byLang[lang][input.dataset.key] = input.value;
        }
      });

      try {
        for (const [lang, translations] of Object.entries(byLang)) {
          const formData = new FormData();
          formData.append('action', 'gw_bulk_save');
          formData.append('nonce', nonce);
          formData.append('lang', lang);
          formData.append('translations', JSON.stringify(translations));

          const response = await fetch(ajaxUrl, {
            method: 'POST',
            body: formData,
          });

          const result = await response.json();
          if (!result.success) {
            throw new Error(result.data || 'Save failed');
          }
        }

        // Mark all as saved
        allInputs.forEach(input => {
          const id = input.dataset.lang + '::' + input.dataset.key;
          if (dirty.has(id)) {
            input.dataset.original = input.value;
            input.classList.remove('is-dirty');
          }
        });
        dirty.clear();
        updateSaveBtn();
        showStatus('All changes saved!');
      } catch (err) {
        showStatus('Error: ' + err.message, true);
        saveAllBtn.disabled = false;
        saveAllBtn.textContent = `Save ${dirty.size} Changes`;
      }
    });
  }

  function showStatus(text, isError) {
    if (!saveStatus) return;
    saveStatus.textContent = text;
    saveStatus.style.color = isError ? '#d63638' : '#00a32a';
    saveStatus.classList.add('is-visible');
    setTimeout(() => {
      saveStatus.classList.remove('is-visible');
    }, 3000);
  }

  function debounce(fn, ms) {
    let timer;
    return function (...args) {
      clearTimeout(timer);
      timer = setTimeout(() => fn.apply(this, args), ms);
    };
  }
})();
