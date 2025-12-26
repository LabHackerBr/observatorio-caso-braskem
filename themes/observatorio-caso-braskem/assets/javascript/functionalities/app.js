import Alpine from 'alpinejs';
import 'iconify-icon';

window.Alpine = Alpine;

window.addEventListener('DOMContentLoaded', () => {
  Alpine.start();
});

document.addEventListener('DOMContentLoaded', () => {

  const labels = {
    pt: { prev: 'Anterior', next: 'Próximo' },
    en: { prev: 'Previous', next: 'Next' },
    es: { prev: 'Anterior', next: 'Siguiente' },
  };

  const lang = (document.documentElement.lang || 'pt').slice(0, 2).toLowerCase();
  const i18n = labels[lang] || labels.pt;


  const normalizeText = (t) => (t || '').replace(/\s+/g, ' ').trim();

  const fixWpmlAria = (root = document) => {
    root.querySelectorAll('.wpml-ls-item a').forEach((link) => {
      const span = link.querySelector('.wpml-ls-native[aria-label]');
      if (span) {
        link.setAttribute('aria-label', span.getAttribute('aria-label'));
        span.removeAttribute('aria-label');
      }

      const visible = normalizeText(link.textContent); // e.g. "PT", "EN", "ES"
      const label = link.getAttribute('aria-label') || '';
      if (visible && label && !label.includes(visible)) {
        link.setAttribute('aria-label', `${visible} — ${label}`);
      }
    });
  };


  const fixSlickButtons = (root = document) => {
    const fixOne = (btn, kind ) => {
      if (!btn) return;

      const desired = kind === 'prev' ? i18n.prev : i18n.next;
      const visible = normalizeText(btn.textContent);

      if (visible) {
        if (visible !== desired) btn.textContent = desired;

        btn.removeAttribute('aria-label');
      } else {
        btn.setAttribute('aria-label', desired);
      }
    };

    root.querySelectorAll('button.slick-prev').forEach((btn) => fixOne(btn, 'prev'));
    root.querySelectorAll('button.slick-next').forEach((btn) => fixOne(btn, 'next'));
  };

  const fixGlider = (root = document) => {
    root.querySelectorAll('.glider-prev, .glider-next').forEach((el) => {
      el.setAttribute('role', 'button');

      if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex', '0');

      if (!el.__a11yBound) {
        el.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            el.click();
          }
        });
        el.__a11yBound = true;
      }

      const desired = el.classList.contains('glider-prev') ? i18n.prev : i18n.next;
      const visible = normalizeText(el.textContent);

      if (!visible) {
        el.setAttribute('aria-label', desired);
      } else {
        el.removeAttribute('aria-label');
      }
    });
  };

  const applyA11yFixes = (root = document) => {
    fixWpmlAria(root);
    fixSlickButtons(root);
    fixGlider(root);
  };

  applyA11yFixes(document);

  const observer = new MutationObserver((mutations) => {
    for (const m of mutations) {
      for (const node of m.addedNodes) {
        if (node && node.nodeType === 1) applyA11yFixes(node);
      }
    }
    applyA11yFixes(document);
  });

  observer.observe(document.body, { childList: true, subtree: true });
});

document.addEventListener('DOMContentLoaded', () => {
  const fixDuplicateUbCounterIds = (root = document) => {
    const selector = '#ub-counter-, [id="ub-counter-"]';
    const nodes = root.querySelectorAll(selector);

    if (!nodes.length) return;

    nodes.forEach((el, index) => {
      if (index === 0) return;

      const newId = `ub-counter-${index + 1}`;
      el.id = newId;
    });
  };

  fixDuplicateUbCounterIds(document);

  const observer = new MutationObserver((mutations) => {
    for (const m of mutations) {
      for (const node of m.addedNodes) {
        if (node && node.nodeType === 1) fixDuplicateUbCounterIds(node);
      }
    }
    fixDuplicateUbCounterIds(document);
  });

  observer.observe(document.body, { childList: true, subtree: true });
});
