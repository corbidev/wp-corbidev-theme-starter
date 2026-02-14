window.CorbiDevModal = (function(){

    let overlay;
    let lastFocused;

    function open(html) {
        lastFocused = document.activeElement;

        overlay = document.createElement('div');
        overlay.className = 'corbidev-modal-overlay';
        overlay.innerHTML = `
            <div class="corbidev-modal" role="dialog" aria-modal="true" tabindex="-1">
                ${html}
            </div>
        `;

        document.body.appendChild(overlay);

        const modal = overlay.querySelector('.corbidev-modal');
        modal.focus();

        overlay.addEventListener('keydown', trapFocus);
    }

    function close() {
        overlay.remove();
        if (lastFocused) lastFocused.focus();
    }

    function trapFocus(e) {
        if (e.key === 'Escape') close();

        if (e.key === 'Tab') {
            const focusable = overlay.querySelectorAll('button, input, [href]');
            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    }

    return { open, close };

})();
