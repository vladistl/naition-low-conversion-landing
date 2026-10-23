// Исследовательский трекер поведения посетителей (ботов симулятора).
// Пишет события в track/collect.php пачками; не влияет на работу формы.
(function () {
    'use strict';

    const ENDPOINT = 'track/collect.php';
    const sid = Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
    const t0 = performance.now();
    const queue = [];
    let seq = 0;

    const now = () => Math.round(performance.now() - t0);

    function push(type, data) {
        queue.push({ n: seq++, t: now(), type, data: data || {} });
    }

    function flush(final) {
        if (!queue.length) {
            return;
        }
        const body = JSON.stringify({ sid, final: !!final, events: queue.splice(0) });
        if (!(navigator.sendBeacon && navigator.sendBeacon(ENDPOINT, new Blob([body], { type: 'text/plain' })))) {
            fetch(ENDPOINT, { method: 'POST', body, keepalive: true }).catch(() => {});
        }
    }

    function describe(el) {
        if (!(el instanceof Element)) {
            return null;
        }
        let s = el.tagName.toLowerCase();
        if (el.id) s += '#' + el.id;
        if (el.classList.length) s += '.' + Array.from(el.classList).join('.');
        if (el.getAttribute('name')) s += '[name=' + el.getAttribute('name') + ']';
        const section = el.closest('section, header, footer');
        const title = section && section.querySelector('h1, h2');
        return {
            sel: s,
            text: (el.innerText || el.value || '').trim().slice(0, 60),
            section: title ? title.textContent.trim().slice(0, 40) : null,
        };
    }

    // --- окружение ---
    push('env', {
        ua: navigator.userAgent,
        webdriver: navigator.webdriver,
        lang: navigator.languages,
        tz: Intl.DateTimeFormat().resolvedOptions().timeZone,
        vw: innerWidth, vh: innerHeight,
        sw: screen.width, sh: screen.height, dpr: devicePixelRatio,
        touch: navigator.maxTouchPoints,
        cores: navigator.hardwareConcurrency,
        plugins: navigator.plugins ? navigator.plugins.length : null,
        ref: document.referrer,
        url: location.href,
        hidden: document.hidden,
    });

    document.addEventListener('DOMContentLoaded', () => {
        push('dom', {
            docH: document.documentElement.scrollHeight,
            form: !!document.getElementById('registration-form'),
            botField: !!document.querySelector('[name=bot_session_id]'),
        });

        // видимость секций: время входа и выхода из вьюпорта
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((e) => {
                    const name = e.target.dataset.trk;
                    push(e.isIntersecting ? 'see' : 'unsee', { s: name, r: Math.round(e.intersectionRatio * 100) / 100 });
                });
            }, { threshold: [0, 0.5] });
            document.querySelectorAll('header, section, footer, .program-module, .btn-register, #registration-form').forEach((el, i) => {
                const h = el.querySelector && el.querySelector('h1, h2, h3');
                el.dataset.trk = (el.className ? el.className.split(' ')[0] : el.tagName.toLowerCase()) + ':' + (h ? h.textContent.trim().slice(0, 30) : i);
                io.observe(el);
            });
        }

        // отслеживаем, кто и как меняет форму (скрытые поля, подстановку значений)
        const form = document.getElementById('registration-form');
        if (form && 'MutationObserver' in window) {
            new MutationObserver((muts) => {
                muts.forEach((m) => m.addedNodes.forEach((node) => {
                    if (node instanceof Element) {
                        push('form-mut', { added: describe(node), value: node.value || null });
                    }
                }));
            }).observe(form, { childList: true, subtree: true });
        }
    });

    window.addEventListener('load', () => push('load', { docH: document.documentElement.scrollHeight }));

    // --- скролл (дискретизация 250 мс) ---
    let lastScroll = 0;
    let maxDepth = 0;
    let scrollTimer = null;
    addEventListener('scroll', () => {
        if (scrollTimer) return;
        scrollTimer = setTimeout(() => {
            scrollTimer = null;
            const y = Math.round(scrollY);
            const depth = Math.round(((y + innerHeight) / document.documentElement.scrollHeight) * 100);
            maxDepth = Math.max(maxDepth, depth);
            push('scroll', { y, d: depth, dy: y - lastScroll });
            lastScroll = y;
        }, 250);
    }, { passive: true });

    addEventListener('wheel', (e) => push('wheel', { dy: Math.round(e.deltaY), trusted: e.isTrusted }), { passive: true });

    // --- мышь: считаем движения, пишем выборку ---
    let moves = 0;
    let lastMoveLog = 0;
    addEventListener('mousemove', (e) => {
        moves++;
        if (performance.now() - lastMoveLog > 500) {
            lastMoveLog = performance.now();
            push('move', { x: e.clientX, y: e.clientY, n: moves, trusted: e.isTrusted });
        }
    }, { passive: true });

    ['mouseover'].forEach((type) => addEventListener(type, (e) => {
        const el = e.target instanceof Element && e.target.closest('.btn, button, a, .pricing-card, .program-module');
        if (el) push('hover', describe(el));
    }, { passive: true }));

    // --- клики, фокус, ввод ---
    addEventListener('click', (e) => {
        push('click', Object.assign({ x: e.clientX, y: e.clientY, trusted: e.isTrusted, detail: e.detail }, describe(e.target)));
    }, true);

    addEventListener('focusin', (e) => push('focus', describe(e.target)), true);
    addEventListener('focusout', (e) => push('blur', Object.assign({ len: (e.target.value || '').length }, describe(e.target))), true);

    const inputStats = {};
    addEventListener('input', (e) => {
        const name = e.target.name || '?';
        const st = inputStats[name] || (inputStats[name] = { first: now(), n: 0 });
        st.n++;
        st.last = now();
        st.len = (e.target.value || '').length;
        st.inputType = e.inputType;
    }, true);
    addEventListener('keydown', (e) => {
        if (e.target && e.target.name) {
            const st = inputStats[e.target.name] || (inputStats[e.target.name] = { first: now(), n: 0 });
            st.keys = (st.keys || 0) + 1;
            st.keyTrusted = e.isTrusted;
        }
    }, true);
    addEventListener('paste', (e) => push('paste', describe(e.target)), true);

    addEventListener('submit', (e) => {
        const f = e.target;
        const fd = new FormData(f);
        const fields = {};
        fd.forEach((v, k) => { fields[k] = typeof v === 'string' ? (k === 'bot_session_id' ? v : v.length) : '?'; });
        push('submit', { fields, inputs: inputStats, trusted: e.isTrusted, submitter: describe(e.submitter) });
        flush();
    }, true);

    // ответ сервера на заявку
    const origFetch = window.fetch;
    window.fetch = function (input, init) {
        const url = typeof input === 'string' ? input : (input && input.url) || '';
        const p = origFetch.apply(this, arguments);
        if (url.indexOf('submit.php') !== -1) {
            p.then((r) => r.clone().json()).then((j) => { push('submit-result', j); flush(); }).catch((err) => push('submit-result', { err: String(err) }));
        }
        return p;
    };

    addEventListener('error', (e) => push('jserror', { msg: e.message, src: e.filename, line: e.lineno }));
    document.addEventListener('visibilitychange', () => push('vis', { hidden: document.hidden }));
    addEventListener('resize', () => push('resize', { vw: innerWidth, vh: innerHeight }));

    function summary() {
        return { maxDepth, moves, inputs: inputStats, y: Math.round(scrollY) };
    }

    // периодический сброс: бот может закрыть вкладку без pagehide
    setInterval(() => { push('tick', summary()); flush(); }, 2000);
    addEventListener('pagehide', () => { push('leave', summary()); flush(true); });
    addEventListener('beforeunload', () => { push('leave', summary()); flush(true); });
})();
