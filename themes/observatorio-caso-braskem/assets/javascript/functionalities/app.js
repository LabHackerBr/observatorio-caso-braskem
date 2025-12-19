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
    // pega cada bloco do Newspack (ou equivalente) e atualiza suas setas
    root.querySelectorAll('.latest-horizontal-posts-block').forEach(block => {
      const prev = block.querySelector('.latest-horizontal-posts-block__arrows .slick-prev, .slick-prev');
      const next = block.querySelector('.latest-horizontal-posts-block__arrows .slick-next, .slick-next');

      if (prev) prev.setAttribute('aria-label', i18n.prev);
      if (next) next.setAttribute('aria-label', i18n.next);
    });

    // fallback: se tiver slick fora desse bloco, tenta atualizar também
    root.querySelectorAll('button.slick-prev').forEach(btn => btn.setAttribute('aria-label', i18n.prev));
    root.querySelectorAll('button.slick-next').forEach(btn => btn.setAttribute('aria-label', i18n.next));
  };

  // roda uma vez no load
  applySlickAria(document);

  // e observa mudanças (sliders que inicializam depois)
  const observer = new MutationObserver((mutations) => {
    for (const m of mutations) {
      for (const node of m.addedNodes) {
        if (node.nodeType !== 1) continue; // element
        // aplica no nó inserido e também no documento (pra cobrir casos em que só classes mudam)
        applySlickAria(node);
      }
    }
    applySlickAria(document);
  });

  observer.observe(document.body, { childList: true, subtree: true });
});


