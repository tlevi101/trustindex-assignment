
(function () {
    function close(modal) {
        if (modal) {
            modal.classList.remove('is-open');
        }
    }

    document.addEventListener('click', function (e) {
        var closer = e.target.closest('[data-close-modal]');
        if (closer) {
            close(closer.closest('.modal'));
            return;
        }
        if (e.target.classList.contains('modal')) {
            close(e.target);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.is-open').forEach(close);
        }
    });
})();
