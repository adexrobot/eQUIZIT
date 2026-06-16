document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
    }

    const savedTheme = localStorage.getItem('equizmona-theme');
    if (savedTheme) {
        html.setAttribute('data-bs-theme', savedTheme);
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            const current = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', current);
            localStorage.setItem('equizmona-theme', current);
        });
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken && window.fetch) {
        window.equizmona = {
            csrf: csrfToken.content,
            post: (url, data) => fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            }).then(r => r.json()),
        };
    }
});
