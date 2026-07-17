(function () {
  var items = Array.prototype.slice.call(document.querySelectorAll('[data-lightbox="true"]'));
  if (!items.length) return;

  var overlay = document.createElement('div');
  overlay.id = 'lb-overlay';
  overlay.innerHTML =
    '<button id="lb-close" aria-label="Close">&times;</button>' +
    '<button id="lb-prev" aria-label="Previous">&#10094;</button>' +
    '<img id="lb-img" src="" alt="">' +
    '<div id="lb-caption"></div>' +
    '<button id="lb-next" aria-label="Next">&#10095;</button>';
  document.body.appendChild(overlay);

  var style = document.createElement('style');
  style.textContent =
    '#lb-overlay{display:none;position:fixed;inset:0;background:rgba(10,14,26,0.92);z-index:9999;align-items:center;justify-content:center;flex-direction:column;}' +
    '#lb-overlay.on{display:flex;}' +
    '#lb-img{max-width:88vw;max-height:80vh;object-fit:contain;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,0.5);}' +
    '#lb-caption{color:#fff;margin-top:1rem;font-family:Inter,sans-serif;font-size:0.95rem;text-align:center;}' +
    '#lb-close{position:absolute;top:1.25rem;right:1.5rem;background:none;border:none;color:#fff;font-size:2.2rem;cursor:pointer;line-height:1;}' +
    '#lb-prev,#lb-next{position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,0.1);border:none;color:#fff;font-size:1.4rem;width:44px;height:44px;border-radius:50%;cursor:pointer;}' +
    '#lb-prev{left:1.5rem;}#lb-next{right:1.5rem;}' +
    '#lb-prev:hover,#lb-next:hover,#lb-close:hover{background:rgba(255,255,255,0.25);}';
  document.head.appendChild(style);

  var imgEl = overlay.querySelector('#lb-img');
  var capEl = overlay.querySelector('#lb-caption');
  var idx = 0;

  function show(i) {
    idx = (i + items.length) % items.length;
    imgEl.src = items[idx].getAttribute('data-src');
    capEl.textContent = items[idx].getAttribute('data-title') || '';
  }
  function open(i) { show(i); overlay.classList.add('on'); }
  function close() { overlay.classList.remove('on'); }

  items.forEach(function (el, i) {
    el.addEventListener('click', function () { open(i); });
  });
  overlay.querySelector('#lb-close').addEventListener('click', close);
  overlay.querySelector('#lb-prev').addEventListener('click', function () { show(idx - 1); });
  overlay.querySelector('#lb-next').addEventListener('click', function () { show(idx + 1); });
  overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
  document.addEventListener('keydown', function (e) {
    if (!overlay.classList.contains('on')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') show(idx - 1);
    if (e.key === 'ArrowRight') show(idx + 1);
  });
})();
