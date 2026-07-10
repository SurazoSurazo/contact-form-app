export function initPhoneField() {
    const form = document.getElementById('contactForm');
    const tel = document.getElementById('tel');
    const parts = [...document.querySelectorAll('.tel-part')];

    if (!form || !tel || parts.length === 0) {
        return;
    }

    const syncTel = () => {
        tel.value = parts.map((input) => input.value.replace(/\D/g, '')).join('');
    };

    parts.forEach((input) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '');
            syncTel();
        });
    });

    form.addEventListener('submit', syncTel);
}
