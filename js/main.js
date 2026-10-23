document.addEventListener('DOMContentLoaded', () => {
    const registrationSection = document.getElementById('registration');
    const form = document.getElementById('registration-form');
    const message = document.getElementById('form-message');
    const registerButtons = document.querySelectorAll('.btn-register');

    registerButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (!registrationSection) {
                return;
            }

            // форма целиком (с кнопкой отправки) оказывается в экране, курсор сразу в первом поле
            const panel = registrationSection.querySelector('.registration-panel') || registrationSection;
            panel.scrollIntoView({ behavior: 'instant', block: 'center' });
            const firstField = form && form.querySelector('input[name="name"]');
            if (firstField) {
                firstField.focus({ preventScroll: true });
            }
        });
    });

    // Программа — аккордеон: клик по модулю раскрывает подробности
    document.querySelectorAll('.program-module').forEach((module) => {
        const toggle = module.querySelector('.module-toggle');
        const body = module.querySelector('.program-module-body');
        module.addEventListener('click', () => {
            const open = module.classList.toggle('is-open');
            if (toggle) toggle.setAttribute('aria-expanded', String(open));
        });
    });

    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const formData = new FormData(form);

        if (submitButton instanceof HTMLButtonElement) {
            submitButton.disabled = true;
        }

        if (message) {
            message.textContent = '';
            message.className = 'form-message';
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (!response.ok || !data.ok) {
                throw new Error(data.error || 'Не удалось отправить заявку.');
            }

            if (message) {
                message.textContent = 'Заявка успешно отправлена. Мы свяжемся с вами в ближайшее время.';
                message.className = 'form-message success';
            }

            form.reset();
        } catch (error) {
            if (message) {
                message.textContent = error instanceof Error ? error.message : 'Не удалось отправить заявку.';
                message.className = 'form-message error';
            }
        } finally {
            if (submitButton instanceof HTMLButtonElement) {
                submitButton.disabled = false;
            }
        }
    });
});
