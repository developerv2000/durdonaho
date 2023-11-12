self.addEventListener('fetch', function (event) {
  self.skipWaiting();
  event.respondWith(
    caches.match(event.request).then(function (response) {
      return response || fetch(event.request);
    })
  );
});
