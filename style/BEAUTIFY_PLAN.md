# Plan: Make Artifact More Beautiful

**Status:** Phases 0–1 in progress (working tree) - continue from there; do not reset  
**North star:** [DESIGN.md](./DESIGN.md) - The Digital Curator  
**Style board:** [the-digital-curator-style-board.png](./the-digital-curator-style-board.png)  
**Review:** [BEAUTIFY_PLAN_REVIEW.md](./BEAUTIFY_PLAN_REVIEW.md) (addressed in this revision)

## Goal

Finish the **Digital Curator** direction already defined in `style/DESIGN.md` and `style/the-digital-curator-style-board.png`, so the product feels like a premium personal gallery rather than a partially restyled admin tool.

This is not a greenfield redesign. Roughly half the system was already in place before this restyle (`ui/style.css` tokens, glass header, dashboard, auth layout, useby card view). The work closes the gap between vision and daily-use pages, and removes the remaining "database UI" cues.

---

## Locked decisions

| Decision | Choice |
|----------|--------|
| Wordmark | **Artifact** (not "Artifact Manager") |
| Collection noun | **Artifacts** (not "Entities") - nav, page titles, and user-facing copy |
| Display face | **Inter only** - get editorial feel from scale and weight; leave `ui/fonts/RobotoSlab-VariableFont_wght.ttf` unused until Phase 6 decide/remove |
| Nav overflow | **"More"** dropdown/panel for tertiary destinations |
| First ship scope | **Phases 0–3** (tokens through tables; DataTables controls may split - see Phase 3) |

---

## Working-tree status (reconciled)

An earlier session started Phases 0–1 before the plan was finalized. **Treat the current uncommitted edits as PR 1 + PR 2 in progress**, not as accidental drift:

| Already on disk | Maps to |
|-----------------|---------|
| CSS tokens → board primary/secondary; page bg `#f8fafc` | Phase 0 |
| `theme-color` / manifest theme → `#30395c` | Phase 0 |
| Wordmark **Artifact**, logo in header, `style.css?v=29` | Phase 0–1 |
| Hierarchical nav + More `<details>`, Escape/outside-click | Phase 1 |
| Guest banner softened (surface-tone, not saturated fill) | Phase 1 |

**Still required before calling PR 1/2 done** (from plan review):

- [x] DESIGN.md palette matches CSS / board (no second source-of-truth split)
- [x] Tertiary vs accent naming disambiguated in DESIGN.md and this plan
- [x] Nav + plan use **Artifacts** (not Entities)
- [x] `offline.html` uses Inter / system sans (not Roboto serif)
- [x] Nav a11y folded into Phase 1 (not deferred to Phase 6)
- [x] SW cache gate folded into Phase 0
- [ ] Visual verify 375px / 1280px before commit
- [x] Residual user-facing "Entity/Entities" in nav, Artifacts index/new/show, analysis, interactions, record-new/edit, aversions (JS ids like `addNewEntity.js` left as internal)

---

## Current product baseline (pre-restyle strengths)

| Area | Status |
|------|--------|
| Design system doc + style board | Exists |
| CSS tokens, glass header, guest banner | In progress restyle |
| Dashboard, auth, useby cards | Strong |
| Buttons, forms, chips, modals, footer | Mostly on-system |
| Brand mark (`ui/assets/logo.svg`) | In header (in progress) |

---

## Gap analysis (why it still feels unfinished)

1. **Palette / doc drift** - Board and CSS target `#30395c` / `#64748b` / `#f8fafc`. DESIGN.md must match (Phase 0).
2. **Brand chrome** - Logo + Artifact wordmark (in progress); finish consistency (offline, emails via `APP_NAME`).
3. **Navigation hierarchy** - In progress; finish a11y and Artifacts label.
4. **Data tables still admin-tool** - Solid headers (partially restyled), DataTables chrome, dense cells.
5. **Uneven page shells** - Support and other sparse pages still bare.
6. **Inline layout leftovers** - Filters / type checkboxes with inline styles.
7. **Signature element** - Hierarchical brand strip (in progress).
8. **Accent underused** - Cyan `--accent` `#33b1e4` mostly focus rings.
9. **Empty / sparse states** - Need deliberate empty-state shell (Phase 2: text + mark only; SVGs in Phase 5).
10. **Motion and app-wide focus** - Defer broad motion to Phase 5; whole-app contrast/focus sweep stays Phase 6.

---

## Creative north star (keep this fixed)

**The Digital Curator** - personal collection management as a quiet gallery, not a spreadsheet app.

- **Tone:** editorial, calm, trustworthy, minimal without austerity.
- **Depth:** surface stacking and ambient shadow, not heavy borders.
- **Type:** Inter scale with catalog-style labels (all-caps, tracked).
- **Signature risk (one bold choice):** refined **brand strip + hierarchical nav** - logo mark, primary CTAs, quiet secondary destinations, and a **More** panel for tertiary links.

Avoid inventing a second aesthetic. Stay on Digital Curator.

---

## Design system alignment

### Palette (target - single source of truth)

Authoritative colors live in `ui/style.css` `:root` and must match DESIGN.md + the style board:

| Token | Target | Role |
|-------|--------|------|
| `--primary` | `#30395c` | Board Primary - actions, brand |
| `--primary-soft` | `#3d4668` | Primary hover / gradient companion |
| `--secondary` | `#64748b` | Soft slate - supporting text/nav |
| `--tertiary` | `#002939` | Dark teal - muted anchors / chip grounding (not cyan) |
| `--accent` | `#33b1e4` | Board cyan - focus rings, tiny highlights only |
| `--page-bg` | `#f8fafc` | Board Neutral |
| surfaces | existing ladder | tonal stacking |

**Naming rule:** "Tertiary" always means the dark teal token (`--tertiary`). The cyan is always **accent** (`--accent`). Never call cyan "tertiary" in docs or code comments.

Update `theme-color` meta and PWA `theme_color` / `background_color` to match. Capacitor/PWA status bar will pick up `#30395c` - confirm splash/theme in Capacitor config does not clash (no shell redesign required).

### Typography

- **Inter only** for UI and body.
- Catalog labels: all-caps, increased letter-spacing.
- `offline.html` and any standalone pages must not use Roboto / Raleway / serif stacks.

### Navigation model

```
[logo] Artifact     [Interact by date] [Record]  ·  Artifacts  Interactions  …  [More ▾]   [username]
```

| Tier | Links | Treatment |
|------|-------|-----------|
| **Primary** | Interact by date, Record interaction | Stronger pills / elevated surface |
| **Secondary** | Artifacts, Interactions, To get rid of, Analysis | Quieter text or soft chips |
| **More** | Types, Users, Support, Settings, Logout (guest: Types, Exit guest) | Dropdown / details panel |

Mobile: burger drawer groups the same tiers with section labels.

### Page shell pattern

```
section-label   (e.g. QUEUE / COLLECTION / ANALYSIS)
h1
optional lede (1 sentence)
optional page-header-actions
```

Content in surface cards or tonal panels (except intentional full-bleed tables).

---

## Implementation phases

### Phase 0 - Align the system (0.5–1 day) - **in progress**

**Files:** `ui/style.css`, `style/DESIGN.md`, `private/shared/header.php`, `ui/manifest.json`, `ui/sw.js`, `ui/offline.html`, `APP_NAME`

1. Reconcile CSS tokens with the style board; **update DESIGN.md in the same change** so docs never lie about live colors.
2. Disambiguate tertiary vs accent in DESIGN.md and this plan.
3. Audit contrast (see Success criteria).
4. Rename product wordmark to **Artifact** (`APP_NAME`, titles, offline, manifest).
5. Bump `style.css?v=` when CSS changes land.
6. **SW cache gate:** confirm installed PWA users receive new CSS after a `?v=` bump:
   - SW pre-caches `/style.css` **without** query string; pages load `style.css?v=N`.
   - On every CSS-shipping PR: bump `CACHE_NAME` in `ui/sw.js` (e.g. `artifact-manager-v5`) so activate clears old caches, **and** verify network + hard refresh shows new styles.
   - Prefer stale-while-revalidate still updates CSS within a session; do not rely on `?v=` alone for SW-controlled clients.
7. Confirm logo SVG at 24–32px in header.
8. Offline page: Inter (or system-ui), board colors - no mixed font families.

**Exit criteria:** One source of truth for colors (board = DESIGN.md = CSS); product name Artifact; SW bump procedure documented and used.

### Phase 1 - Brand chrome and navigation - **in progress**

**Files:** `private/shared/header.php`, `private/shared/footer.php`, `ui/style.css`

1. Logo mark + **Artifact** wordmark; tagline retained.
2. Hierarchical nav (primary / secondary / **More**).
3. Secondary label **Artifacts** (not Entities); align remaining user-facing Entity strings in key pages as part of this PR closeout.
4. Mobile drawer: section labels, large tap targets, Escape closes menu/More.
5. **Nav accessibility (in this phase, not Phase 6):**
   - `aria-label` on main nav (done).
   - Escape + outside-click close (done).
   - Focus returns to More control / burger when closed.
   - Keyboard: Tab through More panel links while open; Escape closes and restores focus.
   - Prefer evaluating button + `aria-expanded` + `role="menu"` if `<details>` fails SR smoke test; keep visual design either way.
6. Guest banner: **acceptance** - surface-tone background (`rgba(primary, ~0.08)` or equivalent) with ghost outline, primary text; **not** a saturated gradient alert bar. Links use accent underline, not neon-on-navy.
7. Footer surface-anchored; wordmark Artifact.

**Exit criteria:** First paint intentional without scrolling; nav usable by keyboard and screen reader smoke test; guest banner matches acceptance tell.

### Phase 2 - Shared page components

**Files:** `ui/style.css`, light markup on sparse pages

- `.page-header` / `.section-label`, `.surface-panel`, `.empty-state`, `.filter-panel`, `.type-chip-group`
- **Empty states in Phase 2:** mark (logo or simple text label) + title + one action. Must look finished **without** Phase 5 SVGs.
- Apply to: Support, Types, Users, Settings, Archive, To get rid of, Explore.

**Exit criteria:** Every listed sparse page uses `.page-header` + a surface container; no orphaned bare `h1` pages.

### Phase 3 - Tables and dense data - **end of first ship**

**Files:** `ui/style.css`, DataTables overrides, light markup as needed

1. Table headers: tonal surface + catalog labels (not solid primary slab).
2. Row separation via tonal shift / whitespace (no-line rule).
3. Status chips catalog-label style.
4. Horizontal scroll containers if needed.
5. Card-view parity on small screens.
6. Print styles still clean after table restyle (do not leave this only for Phase 6).

**Optional sub-PR 3b (if Phase 3 overruns):** DataTables length/filter/paginate restyle alone, so Phases 0–2 can ship without waiting on DataTables chrome.

**Estimate caution:** 1.5–2 days is tight; plan on **2–3 days** or split 3b. Prefer shipping tonal tables first, DataTables polish second.

**Exit criteria:** Useby, Interactions, Artifacts list feel curated; print not broken.

### Phase 4 - Hero flows (after first ship)

Dashboard, Login/Register, Useby filters, Record interaction, Analysis surface language.

### Phase 5 - Delight, motion, assets (after first ship)

Motion with `prefers-reduced-motion`; focus-visible accent; empty-state SVGs; optional muted type colors; PWA icon check.

### Phase 6 - Cleanup (after first ship)

1. Inline `style=` layout purge.
2. Hardcoded hex → tokens.
3. Whole-app contrast / focus-order / form errors (nav a11y already done in Phase 1).
4. **Roboto Slab file:** remove `ui/fonts/RobotoSlab-VariableFont_wght.ttf` from repo and SW cache, **or** document in DESIGN.md why it is retained.
5. Capacitor splash/theme spot-check if status bar tint looks wrong.

---

## What not to do

- Do not rewrite PHP domain logic or schema "while we're in there."
- Do not introduce a CSS framework.
- Do not add a second font family across body text.
- Do not dark-mode unless requested.
- Do not animate everything.
- Do not redesign the Capacitor Android shell in the same pass (theme_color pickup is fine).

---

## PR-sized execution order

| PR | Scope | Notes |
|----|--------|-------|
| 1 | Token alignment + DESIGN.md + Artifact rename + offline + SW cache bump | Finish Phase 0 |
| 2 | Header logo + hierarchical nav + More + a11y + Artifacts noun | Finish Phase 1 |
| 3 | Page shell + empty states on sparse pages | Phase 2 |
| 4 | Tables restyle (core) | Phase 3 |
| 4b (optional) | DataTables controls only | If 4 overruns |
| 5+ | Hero flows, motion, cleanup | Phases 4–6 |

Each PR: bump `style.css?v=` **and** `CACHE_NAME` in `sw.js`; visual check at **375px and 1280px** (browser devtools device mode or headless screenshot of dashboard, useby, login, one listing page).

---

## Success criteria (first ship)

### North star

1. First 2 seconds: logo + calm hierarchy, not a wall of pills.
2. Daily screens feel like one product.
3. Design system rules visible without reading the doc.

### Objective gates (must pass before calling first ship done)

| Gate | Check |
|------|--------|
| **Contrast** | WCAG AA: body text ≥ 4.5:1 on surface ladder; large text/UI ≥ 3:1. Verify pairs: `--text` on `--page-bg` / `--surface-lowest`; `--text-soft` on surfaces; primary button text on `--primary`; **overdue red (`--danger`) on white/surface-lowest**. |
| **No orphaned pages** | Support, Types, Users, Archive, To-get-rid-of, Explore each use `.page-header` (or equivalent) + surface container. |
| **Viewport** | Dashboard, useby, login, Artifacts list checked at **375px** and **1280px** (Chrome/Firefox responsive mode acceptable; screenshots optional but preferred). |
| **SW CSS** | After deploy: hard reload once; second load still shows new chrome; `CACHE_NAME` was bumped with the CSS PR. |
| **Noun** | No user-facing "Entities" in main nav or Artifacts index title. |
| **Fonts** | No Roboto/Raleway/serif stacks on online or offline shell pages. |

---

## Effort estimate

| Phase | Rough effort |
|-------|----------------|
| 0 Token alignment + docs + SW | 0.5 day (mostly done) |
| 1 Brand + nav + a11y | 1–1.5 days (mostly done) |
| 2 Page shell + sparse pages | 1 day |
| 3 Tables | **2–3 days** (or 1.5 + optional 3b) |
| **First ship total** | **~5–6 focused days** |
| 4–6 (later) | ~2–4 more days |

---

## Key files

| Role | Path |
|------|------|
| Design system | `style/DESIGN.md` |
| Style board | `style/the-digital-curator-style-board.png` |
| This plan | `style/BEAUTIFY_PLAN.md` |
| Plan review | `style/BEAUTIFY_PLAN_REVIEW.md` |
| Global CSS | `ui/style.css` |
| Shell | `private/shared/header.php`, `private/shared/footer.php` |
| SW | `ui/sw.js` |
| Offline | `ui/offline.html` |
| Showcase | `ui/index.php`, `ui/login.php`, `ui/artifacts/useby.php`, `ui/uses/record-new.php`, `ui/analysis.php` |
| Sparse pages | `ui/support.php`, `ui/types/index.php`, `ui/users/index.php`, `ui/artifacts/to-get-rid-of.php`, `ui/explore/index.php`, `ui/archive.php` |
| Brand | `ui/assets/logo.svg`, icons |
| App name | `private/environment_variables.php` (`APP_NAME`) |

---

## Display face note

**Inter only** for this ship. Prefer type scale and weight over a second family. Phase 6 decides whether to delete the unused Roboto Slab file.
