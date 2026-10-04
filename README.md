# bulbula.et

Landing ("coming soon") page for **Bulbula** — a business discovery platform for Ethiopia.

The production product will be built on **Laravel (latest)**. This repo currently holds the
static pre-launch page only.

## Stack (landing page)

- Hand-written HTML / CSS / JS — no build step
- [GSAP 3](https://gsap.com) — intro timeline, headline reveal, orb drift, marquee, counters
- [Motion](https://motion.dev) (the vanilla engine behind Framer Motion) — springy hover / press / success micro-interactions
- Libraries are vendored in `assets/vendor/` so the page works offline and on any static host

## Structure

```
index.html
assets/
  css/styles.css
  js/main.js
  vendor/gsap.min.js
  vendor/motion.min.js
```

## Run locally

```bash
python3 -m http.server 8000
# or
npx serve .
```

Then open http://localhost:8000

## Wiring the waitlist to a backend

`assets/js/main.js` has an `ENDPOINT` constant near the bottom. Leave it empty and emails are
only kept in `localStorage`. Set it to a URL and the form POSTs `{"email": "..."}` as JSON:

```js
var ENDPOINT = "https://api.bulbula.et/waitlist";
```

Matching Laravel route:

```php
Route::post('/waitlist', function (Request $request) {
    $data = $request->validate(['email' => 'required|email|unique:waitlist,email']);
    Waitlist::create($data);
    return response()->json(['ok' => true]);
});
```

## Deploy

Any static host works — GitHub Pages, Netlify, Cloudflare Pages, or an Nginx vhost on the same
server that will later run Laravel. For GitHub Pages: Settings → Pages → Deploy from branch → `main` / root.

## To do before launch

- [ ] Replace social links in the footer with real profiles
- [ ] Add `assets/img/og.png` (1200×630) for link previews
- [ ] Point `ENDPOINT` at the real waitlist API
- [ ] Amharic version of the copy
