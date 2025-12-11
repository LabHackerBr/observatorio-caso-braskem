import Alpine from 'alpinejs';

import 'iconify-icon';

window.Alpine = Alpine;

window.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});

document.addEventListener('DOMContentLoaded', function () {

  const fieldsets = document.querySelectorAll('fieldset');

  fieldsets.forEach(function (fs, index) {
    const hasLegend = fs.querySelector('legend');
    const hasAria = fs.hasAttribute('aria-label') || fs.hasAttribute('aria-labelledby');

    if (hasLegend || hasAria) return;

    let text = '';

    const heading = fs.querySelector('h1, h2, h3, h4, h5, h6');
    if (heading) text = heading.textContent.trim();

    if (!text) {
      const label = fs.querySelector('label');
      if (label) text = label.textContent.trim();
    }

    if (!text) {
      text = 'Grupo de campos ' + (index + 1);
    }

    const legend = document.createElement('legend');
    legend.className = 'screen-reader-text';
    legend.textContent = text;

    fs.insertBefore(legend, fs.firstChild);
  });

});
