// ─────────────────────────────────────────────────────────────
// Яндекс.Метрика — единый счётчик для всех страниц сайта.
//
// ШАГ ЗАПУСКА: впишите номер вашего счётчика вместо 0.
// Где взять номер — см. файл ЗАПУСК_ПОШАГОВО.md, раздел «Метрика».
// Пока тут 0 — счётчик выключен и ничего не ломает.
// ─────────────────────────────────────────────────────────────

var YM_ID = 0; // ← СЮДА номер счётчика, например: var YM_ID = 98765432;

if (YM_ID) {
  (function (m, e, t, r, i, k, a) {
    m[i] = m[i] || function () { (m[i].a = m[i].a || []).push(arguments); };
    m[i].l = 1 * new Date();
    for (var j = 0; j < document.scripts.length; j++) {
      if (document.scripts[j].src === r) { return; }
    }
    k = e.createElement(t); a = e.getElementsByTagName(t)[0];
    k.async = 1; k.src = r; a.parentNode.insertBefore(k, a);
  })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

  ym(YM_ID, "init", {
    clickmap: true,
    trackLinks: true,
    accurateTrackBounce: true,
    webvisor: true
  });
}
