function initRecaptcha() {
    if (typeof grecaptcha !== 'undefined' && document.getElementById('custom-recaptcha')) {
        grecaptcha.render('custom-recaptcha', {
            sitekey: recaptcha_vars.site_key,
            size: 'normal',
            callback: function(token) {
                console.log('Token gerado:', token);
            }
        });
    } else {
        console.error('reCAPTCHA não pôde ser inicializado');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('custom-recaptcha')) {
        if (typeof grecaptcha === 'undefined') {
            let script = document.createElement('script');
            script.src = 'https://www.google.com/recaptcha/api.js?onload=initRecaptcha&render=explicit&hl=pt-BR';
            script.async = true;
            script.onerror = function() {
                console.error('Falha ao carregar a API reCAPTCHA');
            };
            document.head.appendChild(script);
        } else {
            initRecaptcha();
        }
    }
});
