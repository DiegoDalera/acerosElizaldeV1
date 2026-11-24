(function () {
  var b = document.querySelector('.burger'), n = document.getElementById('menu');
  function setMenu(open) {
    n.classList.toggle('open', open);
    b.setAttribute('aria-expanded', open ? 'true' : 'false');
    b.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
  }
  if (b && n) {
    b.addEventListener('click', function () { setMenu(!n.classList.contains('open')); });
    n.addEventListener('click', function (e) { if (e.target.tagName === 'A') setMenu(false); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && n.classList.contains('open')) { setMenu(false); b.focus(); }
    });
  }
  var f = document.getElementById('cot');
  if (f) {
    f.addEventListener('submit', function () {
      var btn = f.querySelector('button[type=submit]');
      if (btn) { btn.setAttribute('aria-busy', 'true'); btn.firstChild.nodeValue = 'Enviando… '; }
    });
  }
  var els = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('in'); }); return; }
  var io = new IntersectionObserver(function (en) {
    en.forEach(function (x) { if (x.isIntersecting) { x.target.classList.add('in'); io.unobserve(x.target); } });
  }, { threshold: 0.12 });
  els.forEach(function (e) { io.observe(e); });
})();
