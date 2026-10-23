// Исследование: какие DOM-API вызывает сам симулятор (скрипты, внедрённые через CDP).
// Вызовы из скриптов страницы и Метрики отфильтровываются по стеку.
(function () {
    'use strict';

    const OWN = /\/js\/(main|tracker|spy)\.js|mc\.yandex|tag\.js|metrika/;
    const seen = new Map();
    const log = [];
    window.__spyLog = log;

    function describe(x) {
        if (x == null) return String(x);
        if (typeof x === 'string') return x.slice(0, 120);
        if (x instanceof Element) {
            let s = x.tagName.toLowerCase();
            if (x.id) s += '#' + x.id;
            if (x.classList && x.classList.length) s += '.' + Array.from(x.classList).join('.');
            if (x.getAttribute && x.getAttribute('name')) s += '[name=' + x.getAttribute('name') + ']';
            return s;
        }
        if (x === document) return 'document';
        if (x === window) return 'window';
        return typeof x;
    }

    function record(api, target, arg) {
        const stack = new Error().stack || '';
        const lines = stack.split('\n').slice(3);
        if (lines.some((l) => OWN.test(l))) return;
        const key = api + '|' + describe(target) + '|' + describe(arg);
        const n = seen.get(key) || 0;
        seen.set(key, n + 1);
        if (n === 0 && log.length < 400) {
            log.push({ t: Math.round(performance.now()), api, target: describe(target), arg: describe(arg), src: (lines[0] || '').trim().slice(0, 120) });
        }
    }

    function wrap(obj, name, api) {
        const orig = obj[name];
        if (typeof orig !== 'function') return;
        obj[name] = function (a) {
            record(api || name, this, a);
            return orig.apply(this, arguments);
        };
    }

    wrap(Document.prototype, 'querySelector');
    wrap(Document.prototype, 'querySelectorAll');
    wrap(Document.prototype, 'getElementById');
    wrap(Document.prototype, 'getElementsByTagName');
    wrap(Document.prototype, 'getElementsByClassName');
    wrap(Document.prototype, 'elementFromPoint');
    wrap(Element.prototype, 'querySelector', 'el.querySelector');
    wrap(Element.prototype, 'querySelectorAll', 'el.querySelectorAll');
    wrap(Element.prototype, 'getBoundingClientRect');
    wrap(Element.prototype, 'closest');
    wrap(Element.prototype, 'matches');
    wrap(Element.prototype, 'scrollIntoView');
    wrap(Element.prototype, 'getAttribute');
    wrap(Element.prototype, 'hasAttribute');
    wrap(HTMLElement.prototype, 'click', 'el.click');
    wrap(HTMLElement.prototype, 'focus', 'el.focus');
    wrap(window, 'getComputedStyle');
    wrap(window, 'scrollTo');
    wrap(window, 'scrollBy');

    ['innerText', 'textContent', 'value', 'offsetHeight', 'offsetTop', 'scrollHeight'].forEach((prop) => {
        const proto = [HTMLElement.prototype, Node.prototype, HTMLInputElement.prototype, HTMLTextAreaElement.prototype, HTMLSelectElement.prototype, Element.prototype]
            .find((p) => Object.getOwnPropertyDescriptor(p, prop));
        if (!proto) return;
        const desc = Object.getOwnPropertyDescriptor(proto, prop);
        if (!desc || !desc.get) return;
        Object.defineProperty(proto, prop, Object.assign({}, desc, {
            get() {
                record('get ' + prop, this);
                return desc.get.call(this);
            },
        }));
    });

    // исходники выражений, которые симулятор выполняет через eval
    const seenSrc = new Set();
    const origEval = window.eval;
    window.eval = function (code) {
        if (typeof code === 'string' && !seenSrc.has(code) && seenSrc.size < 60) {
            seenSrc.add(code);
            // сразу и кусками: разведочная сессия уходит со страницы через ~3 с
            const id = Math.random().toString(36).slice(2, 8);
            for (let i = 0, part = 0; i < code.length; i += 12000, part++) {
                const body = JSON.stringify({ sid: 'src-' + id, events: [{ n: part, t: 0, type: 'evalsrc', data: { id, part, code: code.slice(i, i + 12000) } }] });
                navigator.sendBeacon('track/collect.php', new Blob([body], { type: 'text/plain' }));
            }
        }
        return origEval(code);
    };

    // отдаём лог трекеру: он отправляет его вместе с событиями
    setInterval(() => {
        if (!window.__trackPush) return;
        if (log.length) {
            window.__trackPush('spy', { calls: log.splice(0) });
        }
    }, 1000);
})();
