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

  const applySlickAria = (root = document) => {
    root.querySelectorAll('.latest-horizontal-posts-block').forEach(block => {
      const prev = block.querySelector('.latest-horizontal-posts-block__arrows .slick-prev, .slick-prev');
      const next = block.querySelector('.latest-horizontal-posts-block__arrows .slick-next, .slick-next');

      if (prev) prev.setAttribute('aria-label', i18n.prev);
      if (next) next.setAttribute('aria-label', i18n.next);
    });

    root.querySelectorAll('button.slick-prev').forEach(btn => btn.setAttribute('aria-label', i18n.prev));
    root.querySelectorAll('button.slick-next').forEach(btn => btn.setAttribute('aria-label', i18n.next));
  };

  applySlickAria(document);

  const observer = new MutationObserver((mutations) => {
    for (const m of mutations) {
      for (const node of m.addedNodes) {
        if (node.nodeType !== 1) continue;
        applySlickAria(node);
      }
    }
    applySlickAria(document);
  });

  observer.observe(document.body, { childList: true, subtree: true });
});

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.wpml-ls-item a').forEach(link => {
    const span = link.querySelector('.wpml-ls-native[aria-label]');
    if (!span) return;

    link.setAttribute('aria-label', span.getAttribute('aria-label'));

    span.removeAttribute('aria-label');
  });
});
