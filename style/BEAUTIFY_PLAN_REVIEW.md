# Review: BEAUTIFY_PLAN.md

**Reviewed:** 2026-07-29  
**Addressed:** 2026-07-29 (see Resolution below)  
**Subject:** [`style/BEAUTIFY_PLAN.md`](./BEAUTIFY_PLAN.md)  
**Also read:** [`style/DESIGN.md`](./DESIGN.md), current uncommitted working tree (`header.php`, `manifest.json`, `offline.html`, `style.css`)

---

## Resolution

| Issue | Action taken |
|-------|----------------|
| 1. Hold vs working tree | Plan **Status** set to "Phases 0–1 in progress"; working tree treated as PR 1+2, not reset |
| 2. Palette / tertiary split | `DESIGN.md` palette updated to `#30395c` / `#64748b` / accent cyan; tertiary = dark teal only |
| 3. Entities vs Artifacts | Nav, plan, and main user-facing `ui/` strings use **Artifacts** |
| 4. Nav a11y in Phase 1 | Plan Phase 1 includes focus return, menu roles, keyboard; header script implements close+focus restore |
| 5. SW cache in Phase 0 | Plan Phase 0 SW gate; `sw.js` → `artifact-manager-v5`, RobotoSlab dropped from pre-cache |
| 6. Objective success criteria | Added contrast, orphan pages, viewport, SW, noun, fonts gates to plan |
| 7. Table estimate | Phase 3 estimate 2–3 days + optional PR 4b for DataTables controls |
| Smaller: offline fonts | `offline.html` → Inter / system-ui + board colors |
| Smaller: guest banner | Plan acceptance = surface-tone (matches current CSS) |
| Smaller: Roboto Slab file | Phase 6 decide/remove |
| Smaller: empty states | Phase 2 must look finished without Phase 5 SVGs |

Remaining for PR 1/2 commit: visual check at 375px / 1280px, then commit.

---

## Verdict

The plan is strong and ready to execute. It is well-sequenced, scoped against creep, and grounded in an existing design system rather than reinventing one. Ship it roughly as written.

The one thing to fix before touching anything else is a bookkeeping problem, not a design problem: the plan says "do not implement until asked," but the working tree already contains most of Phases 0-1, and that partial work has opened a new colour-source-of-truth split that the plan exists to close. Reconcile that first (see Issue 1 and Issue 2), then proceed.

---

## What's strong

- **Phased with PR-sized cut lines.** The "PR-sized execution order" table (plan lines 213-223) maps each phase to a shippable PR with a defined first-ship boundary (Phases 0-3, PR 4). This is the single most useful part of the doc - it makes the work resumable and reviewable.
- **Locked decisions up front** (lines 15-23). Inter-only, "More" dropdown, Artifact wordmark, first-ship scope. Removes re-litigation mid-build.
- **Explicit anti-scope-creep list** (lines 202-209). "Do not rewrite PHP domain logic while we're in there" and "no CSS framework" are exactly the guardrails a restyle needs.
- **Gap analysis is concrete** (lines 42-53). It names the specific "database UI" tells (solid primary table headers, zebra stripes, DataTables chrome) rather than gesturing at "make it prettier."
- **Faithful to DESIGN.md.** The nav model, no-line rule, tonal layering, and catalog labels all trace back to the design system doc. The in-progress header implementation matches the plan's nav ASCII (line 94) closely.

---

## Material issues (ranked)

### 1. The "implementation hold" contradicts the working tree

The plan status is "Approved - document only" and it closes with "Do not implement until explicitly asked" (lines 274-277). But `git status` shows `header.php`, `manifest.json`, `offline.html`, and `style.css` already carry Phase 0 + most of Phase 1:

- `theme-color` and manifest `theme_color` moved to `#30395c`; `background_color` to `#f8fafc` (Phase 0 step 1, palette target).
- Wordmark renamed to **Artifact**, logo mark added to header, `style.css?v=` bumped to 29 (Phase 0 steps 3-5).
- Hierarchical nav built: `nav-group-primary` / `-secondary` / `More` `<details>` panel, plus Escape/outside-click handling (Phase 1 steps 2-3).

The plan already anticipates this ("An earlier session may have partially edited chrome/CSS before a pause," line 277). Decide explicitly:

- **Recommended:** treat the working tree as PR 1 + PR 2 in progress, verify it against the plan, and update the plan's Status line from "document only" to "Phase 0-1 in progress." Do not leave a doc that says "don't implement" sitting on top of a half-implemented change.
- Or reset the working tree if the intent really is to start clean.

Either way, the current state - approved plan says "hold," disk says "half-built" - is the thing most likely to cause a future session to redo or clobber work.

### 2. The palette change created a new source-of-truth split (the exact thing Phase 0 is meant to close)

Phase 0's exit criterion is "One source of truth for colors; board and CSS agree" (line 132). The in-progress edits moved CSS `--primary` to `#30395c`, but **DESIGN.md still says Primary `#1a2345`** (DESIGN.md line 17). So we have swapped one drift (board vs CSS) for another (DESIGN.md vs CSS).

Phase 0 step 1 already calls for this ("Reconcile CSS tokens with the style board; note any deliberate deviations in DESIGN.md," line 126) - it just hasn't been done. **Update DESIGN.md's palette section in the same PR as the token change**, or the doc actively lies about the live colours. This is a one-file edit; do not let it slip to a later phase.

Related terminology trap to fix while in DESIGN.md: "Tertiary" means two different things across the docs. DESIGN.md line 19 calls `#002939` (dark teal) the Tertiary; the plan's palette table (line 79) and gap item 8 call the cyan accent `#33b1e4` the "Board Tertiary." Pick one meaning for "tertiary" and rename the other (e.g. "accent") so nobody wires up the wrong token.

### 3. Naming: the plan and the new nav still say "Entities," but the product just renamed to "Artifacts"

Commit `8a07e53` ("Rename entity/entities to artifact/artifacts throughout the UI") is recent, yet:

- The plan's nav model (line 94, line 100) and Key Files still use "Entities."
- The **in-progress header still renders `>Entities<`** as the secondary nav label (`private/shared/header.php`).
- `grep` still finds "Entities"/"Entity" across `ui/` (e.g. `artifacts/index.php`, `analysis.php`, `uses/modules/addNewEntity.js`).

This is the same "wordmark rename" hygiene the plan cares about for "Artifact Manager -> Artifact." Decide the user-facing noun (almost certainly "Artifacts" given the rename commit and the "Artifact" wordmark), then make the nav label, the plan text, and the residual `ui/` strings agree. Right now three sources disagree.

### 4. Don't defer navigation accessibility to Phase 6

A11y is parked in Phase 6 cleanup (line 198), but the nav is **rebuilt in Phase 1** as a `<details>`/`<summary>` "More" menu. `<details>` used as a menu has real screen-reader and focus-management quirks, and the grouped primary/secondary tiers need to be announced sensibly. Fixing this after four PRs means auditing nav twice.

The in-progress code already does the easy 80% (`aria-label="Main"`, Escape-to-close, outside-click) - good. Fold the rest of the nav a11y (focus return on close, keyboard traversal of the More panel, whether `<details>` should instead be a button + `aria-expanded` menu) **into Phase 1** and leave only the whole-app contrast/focus-order sweep for Phase 6.

### 5. Coordinate `style.css?v=` with the service-worker cache, or PWA users get stale CSS

This is a PWA with an offline page and a service worker (commit `d2407a6` "Bump service-worker cache to v4"). The plan bumps `style.css?v=` on every PR (line 223) and mentions SW cache strategy only in Phase 6 (line 197). If the service worker caches `style.css` by a URL the `?v=` bump doesn't invalidate, installed users will keep serving old CSS through the whole restyle and the "visual check" won't reflect what they see.

Move "confirm the SW actually serves the new `style.css` after a `?v=` bump" **up to Phase 0**, next to the cache-version bump. It's a correctness gate for every later phase, not cleanup.

### 6. Success criteria are mostly subjective; add a few objective gates

The success criteria (lines 227-234) are good north-star language ("feel like one product," "not a wall of pills") but only one is testable, and even that is unquantified. Add concrete acceptance checks the first-ship PR must pass:

- **Contrast:** state the target (WCAG AA, 4.5:1 body / 3:1 large text) and the specific pairs to verify - the plan already flags the risky ones (line 44, "overdue red on white"; text on the surface ladder).
- **No orphaned pages:** enumerable - Support, Types, Users, Archive, To-get-rid-of, Explore all use `.page-header` + a surface container (the plan lists these at line 159).
- **375px / 1280px** already specified (line 223) - keep, and say *how* (headless screenshot vs device).

### 7. The Phase 3 table estimate is the one to distrust

1.5-2 days for tables (line 245) is the tightest estimate against the most work: DataTables control overrides (length/filter/paginate), no-line row separation, status chips, horizontal-scroll containers, card-view parity, **and** keeping print styles clean (Phase 6 item 3 depends on this). If any estimate slips, it's this one. Consider splitting "DataTables control restyle" into its own sub-PR so a table overrun doesn't block the Phase 0-2 wins from shipping.

---

## Smaller notes

- **`offline.html` uses `font-family: "Roboto", serif`** (still, after the edit). That contradicts the Inter-only lock (line 20) and DESIGN.md's "don't mix font families." Roboto isn't even loaded, so it falls back to serif - off-system. Swap to Inter / system sans while the file is already open.
- **Guest-banner softening** (Phase 1 step 4) has no acceptance description beyond "less alert bar." Give it one concrete tell (e.g. surface-tone background instead of a saturated fill) so it's reviewable.
- **`RobotoSlab-VariableFont_wght.ttf` stays shipped but unused** (lines 38, 269). Fine for this ship, but add a Phase 6 line to either remove the unused font file or document why it's retained, so it's a decision and not a leftover.
- **Empty-state SVGs are Phase 5** (line 187) but `.empty-state` markup lands in Phase 2 (line 155). Make sure Phase 2's empty states degrade gracefully with just a mark + text so they don't look unfinished during the ~2-4 day gap before Phase 5.
- **Android/Capacitor:** the plan correctly scopes the shell out (line 209), but the `theme_color` change to `#30395c` will move the Android status-bar tint on the installed PWA. That's desired, just confirm it doesn't clash with any hardcoded splash/theme colour in the Capacitor config.

---

## Recommended next actions

1. **Reconcile the hold vs the working tree** (Issue 1). Verify the in-progress `header.php`/`manifest.json`/`style.css`/`offline.html` against the plan, then commit as PR 1 + PR 2 and update the plan's Status line - or reset.
2. **Update DESIGN.md's palette to `#30395c`** and resolve the "tertiary" naming collision in the same PR (Issue 2).
3. **Settle the Entities/Artifacts noun** and make nav, plan, and `ui/` strings agree (Issue 3).
4. **Pull nav a11y into Phase 1** and the SW-cache-serves-new-CSS check into Phase 0 (Issues 4-5).
5. Add the objective acceptance gates to the success criteria (Issue 6) before opening PR 1.

Net: no change to the design direction or the phase order - the Digital Curator north star and the PR sequence are sound. The fixes are about closing the gap between what the doc says and what's already on disk, and making the success bar checkable.
