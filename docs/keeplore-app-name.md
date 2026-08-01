# KEEPLORE-NAME.md

*Naming decision record — Stephens Page LLC*
*Decided: August 1, 2026 · Jacob Stephens*

---

## Decision

The possessions tracker formerly known as **Artifact Manager** is renamed **Keeplore**.

| Field | Value |
|---|---|
| Product name | **Keeplore** |
| Pronunciation | **KEEP-lore** (two syllables, stress on first) |
| Primary domain | `keeplore.app` |
| Alias | `keeplore.stephens.page` |
| Repository | `JacobStephens2/keeplore` |
| Bundle identifier | `app.keeplore` |
| Publisher | Stephens Page LLC |
| Tagline | **Know what you own. Use what you keep.** |
| Retired | `artifact-manager`, `artifact-manager-web-app`, `artifact.stephens.page`, `artifact.stewardgoods.com` |

Registered on Porkbun: $8.75/yr, $14.93 renewal. Consistent with the same-day
registration pattern used for `chart35.app`, `quadrille.app`, `nightloch.app`,
and `verelle.app`.

---

## Why the old name had to go

**"Artifact manager" is an occupied product category, not merely a vague name.**

- Artifactory is documented in its own ecosystem as "an artifact management
  repository… like a database management system for binaries."
- GitHub Actions discussions are full of "Artifact not found for name" errors.
- `paulovn/artifact-manager` already exists on GitHub, doing roughly what a
  developer would *assume* this project does.
- Since 2024, "Artifacts" is also an Anthropic product feature.
- Consumer-side, `heyartifact.com` runs an unrelated "Artifact" family-stories
  product.

The practical cost: a hiring manager scanning `JacobStephens2/artifact-manager`
reads CI/CD tooling and scrolls past a 550-commit, zero-AI, 2021–2024 PHP/MySQL
consumer app. The name actively hid the most distinctive thing on the profile.

The word is unownable in both the developer and consumer directions.

---

## The name

**Keep + lore.**

- **Keep** — the verdict. Every review in the app ends in a keep-or-release
  decision. It is the central question of the 90/90 rule.
- **Lore** — the record. The app is a database of accumulated history: every
  logged interaction with every board game, book, film, instrument, and tool,
  plus the interact-by dates derived from it. The lore of an object is what it
  has done in your life.

The name encodes both halves of the product: the decision the app prompts, and
the record the app keeps.

---

## Why Keeplore won

**1. It names the record, which is what the app actually is.**
Users spend the overwhelming majority of their time recording and reviewing.
Discarding is the exit event, not the practice. Every rejected candidate named
the exit event (Doff) or the role (Stewardry) rather than the record.

**2. The search field is effectively empty.**
Exhaustive searching surfaced only a Steam profile, a World of Tanks clan
roster entry, a finance-newsletter tagline ("Keeplore: A curated library of
wisdom"), and Instagram OCR noise. No app-store presence, no GitHub repo, no
trademark hits. The nearest real company is **Keepler**, a data/AI consultancy —
different word, different sector, does not contest the term. A real site with a
real README should own result #1 within weeks.

**3. It preserves the existing tagline verbatim.**
*"Know what you own. Use what you keep."* and *"…generates interact-by dates so
the things you keep stay in use."* The copy was already good; the name was the
only broken part.

**4. It extends.**
"Lore" absorbs item notes, photos, provenance, acquisition history, lending
records, maintenance and warranty data, and household sharing. Every plausible
roadmap feature fits inside the brand without stretching it.

**5. It fits the portfolio register.**
Two syllables, soft consonants, coined-but-parseable — the same family as
Caeven, Velanne, Verelle, Quadrille, Nightloch, Drome, Chart35. A coherent set
of calm coined names reads as deliberate brand craft.

---

## How the decision was made

Three rounds, with web-verified collision checks each round.

### Round 1 — Open field

- The correct semantic root is **keep** — the app's own live tagline already
  said so.
- Avoid inventory/item/belongings vocabulary; the category is saturated (Item
  Tracker, Itemlist, My Stuff, Belongings, Sortly).
- Avoid 90/90-derived names. 90/90 is The Minimalists' actively promoted
  phrase; building the brand on it invites implied-endorsement risk and
  produces a weak, hard-to-register mark. The rule belongs in the description.
- Shortlist: Keepward, Keeplore, Doff, Stewardry, Cullendar.

### Round 2 — Keeplore vs. Doff vs. Stewardry

All three `.app` domains available at $8.75. **Keeplore won unanimously.**

### Round 3 — Keeplore vs. Keepward

Both available at $8.75. Closest call of the process; analyses split 2–1.
**Keeplore won on search ownability.**

---

## Candidates rejected

### Keepward — runner-up, killed by Halo

Genuine strengths: unambiguous pronunciation, tighter tagline symmetry (the
name nearly *is* the second clause), coherent castle imagery (a *keep* is a
fortified tower; a *ward* is the enclosed space within castle walls).

It failed on the exact criterion that motivated the rename:

- **Keepwards are a named class of Sangheili military personnel in Halo canon**,
  with a dedicated Halopedia article and official description as "analogous in
  many ways to an Honor Guard… tasked with maintaining the safety of a region's
  kaidon."
- A **Keepward harness** armor set exists as an Elite armor permutation.
- A **KEEPWARD helmet** shipped as a Halo MCC Season 7 reward, with Microsoft
  trademark notice attached.
- Active multilingual community discussion; a `KEEPWARD LIMITED` company
  registration also exists.

Adopting it would trade an unwinnable search against Artifactory for an
unwinnable search against a franchise with tens of millions of players.
Secondary: "ward" as a verb already means "to keep watch over, guard," making
the compound faintly redundant; the hard `-rd` cluster reads like infrastructure
tooling.

### Doff — killed by a `diff` collision

Right on paper: one syllable, archaic verb meaning "to cast off," a contraction
of *do off*. It failed on four counts:

- **`franklioxygen/doff` on GitHub is "a local-first, offline-ready diff
  workspace."** Renaming *away* from a dev-tooling misread and into a name one
  keystroke from `diff` defeats the entire exercise. `doff` is also a field in
  the TCP header struct (data offset).
- Three existing store apps: dOFF (iOS focus/pet timer), Doff Eventi (Google
  Play), Doff 123 (Google Play). Plus a Windows display-power utility.
- The live meaning has moved. Post-2020, dominant usage is clinical **"don and
  doff"** for PPE, orthotics, and braces.
- A `DOFF OF THE CAP AWARD` mark appears in a class-9 software opposition
  publication.

Above all, it names the wrong half of the product. The 90/90 rule's alternate
name is the *seasonality rule* — the window exists to give items a fair hearing,
not to accelerate disposal.

### Stewardry — right thesis, occupied word, wrong spelling

- **The Stewardry is an actively marketed 18th-century manor house** on the
  Boconnoc Estate in Lostwithiel, Cornwall — a 14-guest luxury rental with
  interiors by Sarah Fortescue, opened 2021, repeatedly covered in travel and
  design press. Not dormant; it holds page one indefinitely.
- **Wrong spelling.** The canonical form is **stewartry** — a former Scottish
  administrative district under a steward's jurisdiction, attested from around
  1473–4. "Stewardry" is listed only as a less-common variant. Permanent
  spelling tax.

It carried the only sentimental argument in the set: the app originally shipped
at `artifact.stewardgoods.com`, so Stewardry would have been a *return* to the
project's first identity. Not enough to outweigh the above. The thesis survives
as copy.

### Also cleared off the board

| Name | Blocker |
|---|---|
| Winnow | Winnow food-waste AI; Winnow & Spruce Organizing; `winnow.app` registered since 2018 |
| Fallow | Best metaphor (dormancy/seasonality) but `fallow-rs/fallow` is an active dev tool; chronic fallow/follow typo |
| Owndex | Already a Home Inventory app on Google Play — direct in-category collision |
| Curio | Curio antique identifier owns `curio.app` |
| Quartermaster | Open QuarterMaster; entire police-quartermaster software category |
| Cullendar | "Cull" reads aggressive; sounds like "calendar" |
| Chattel | Unavoidable "chattel slavery" association |
| Molt, Sundry | Taken by live products |
| Ninety / Ninetyfold / Ninewise | The Minimalists' phrase; implied endorsement; numerals are weak marks |

---

## Known weaknesses of Keeplore (accepted)

1. **Kepler adjacency.** *Keeplore* and *Kepler* (KEP-lər) are visually similar.
   Expect an occasional "like Kepler?" — a conversation-starter, not a
   collision. Different word, different sector, no software conflict.
2. **Mild fantasy-RPG tint.** "-lore" pulls some D&D/gaming search noise.
   Notably milder than Keepward's, which is a *literal* game rank.
3. **Category ambiguity.** Absent the tagline, "Keeplore" could read as a
   collectibles database or heirloom journal. The tagline resolves it in one
   line — always ship them together.
4. **Eight letters.** Slightly long for a domain typed often. Acceptable.

---

## Identity

### Tagline (invariant — predates the rename, survives it)

> **Know what you own. Use what you keep.**

### One-liner

> Keeplore — a self-hosted 90/90 rule tracker for physical possessions.

### Longer description

> Keeplore tracks when you use the things you own, generates interact-by dates
> so what you keep stays in use, and surfaces what may no longer earn its
> place — inspired by The Minimalists' 90/90 rule.

### GitHub repo description

> Keeplore — self-hosted 90/90 rule tracker for physical possessions.
> PHP/MySQL, 550+ commits, zero AI-generated code (2021–2024).

The name deliberately trades searchability for ownability; the repo description
carries the descriptive weight. The final clause is intentional — in 2026 it is
a genuinely rare signal, and a distinctive name makes it more quotable.

### README heading

```markdown
# Keeplore

**Know what you own. Use what you keep.**

Keeplore is a self-hosted possessions tracker inspired by the Minimalists'
90/90 rule. Record when you use the things you own, see what is due for
attention, and decide what still earns its place.
```

---

## Style rules

### Correct

Keeplore · keeplore.app · Keeplore app · Keeplore account · Keeplore Review

### Incorrect

KeepLore · Keep Lore · The Keeplore · Keeplore Manager · Keeplore Inventory

One word, capital K only. Introduce as: *"Keeplore — as in the lore of what you
keep."*

### Preferred descriptors

Personal possessions tracker · Intentional-ownership app · 90/90 rule tracker ·
Self-hosted possessions tracker

### Avoid as descriptors

Inventory manager · Asset-management platform · Artifact manager · Home
inventory database · Decluttering challenge

These either recreate the original naming problem or describe only part of the
product.

---

## Product vocabulary

| Concept | Term |
|---|---|
| A tracked possession | **Item** |
| Recorded interaction | **Use** |
| When an item should next be used | **Interact-by date** *(retain — this is the differentiator)* |
| Periodic pass over items due | **Keeplore Review** |
| Item states | **In Use / At Risk / Overdue** |
| Review verdicts | **Keep / Release / Exempt** |
| Historical activity | **Use history** |
| Group of items | **Collection** |
| Storage place | **Location** |

**Hold "Lore" in reserve.** Do not introduce *lore entry*, *lorekeeper*, or
*unlocking lore* as UI terms. The word may become the natural label for an
item's accumulated notes, photos, and provenance *if and when those features
ship* — but the brand should not pretend they already exist. Keep the UI plain
and let the name carry the personality.

**"Ward"** is available as an optional label for items under active review —
salvaged from the Keepward concept without paying its SEO cost.

---

## Philosophy copy

> Keeplore promotes a practice of personal stewardry: knowing what you own,
> using what you keep, and releasing what no longer earns its place.
> Stewardship, not ownership.

This preserves the Stewardry thesis and the `stewardgoods.com` lineage as copy,
not brand. "90/90" always appears with attribution to The Minimalists, in
descriptions and about pages — never in the wordmark or feature names.

---

## Domains

| Asset | Value |
|---|---|
| Primary | `keeplore.app` (registered, Porkbun) |
| Alias | `keeplore.stephens.page` |
| Legacy | `artifact.stephens.page` → 301 |
| Legacy | `artifact.stewardgoods.com` → retire/redirect |
| Repository | `github.com/JacobStephens2/keeplore` |
| Legacy repo | `artifact-manager-web-app` — archive, do not delete |

### Deliberately NOT registered

| Domain | Reason |
|---|---|
| `keepward.app` | Halo canon collision — not a viable fallback either |
| `doff.app` | `diff` adjacency means it can't serve as insurance |
| `stewardry.app` | Boconnoc property + stewartry spelling split |

Recorded here so the decision is not re-litigated later.

---

## Migration checklist

### Domain and infrastructure
- [x] Register `keeplore.app` — $8.75, Porkbun
- [x] Configure DNS; provision TLS
- [x] Add domain to production web server; set as canonical host
- [ ] Add `keeplore.stephens.page` alias
- [x] **Path-preserving** 301 from `artifact.stephens.page`
      (`/login.php` → `/login.php`)
- [x] Retire / redirect `artifact.stewardgoods.com`
- [ ] Update monitoring, uptime checks, analytics, backups, deploy config

### Application
- [x] Replace visible "Artifact Manager" branding and short-form "Artifact"
- [x] Update page titles, metadata, logos, icons, favicons
- [x] Update transactional email templates and **password-reset URLs**
- [x] Update **cookie domain and session configuration**
- [x] Update OAuth callback URLs if applicable *(none — no OAuth)*
- [x] Update manifests, legal and privacy pages *(manifest + meta; no separate legal pages)*
- [x] Preserve individual tracked objects as "items," not "artifacts" *(UI copy; code paths still `/artifacts/` for now)*
- [x] Ship a temporary "Artifact Manager is now Keeplore" notice

### GitHub
- [x] Rename `artifact-manager` → `keeplore` (GitHub 301s the old path; stars,
      issues, and clone URLs survive)
- [ ] Archive `artifact-manager-web-app` — same treatment as Magisterium: a
      deliberate portfolio artifact, not a deletion
- [ ] Update description, website field, README links, screenshots, badges
- [ ] Update Actions secrets and environment URLs; update local remotes
- [ ] Add topics: `minimalism`, `self-hosted`, `php`, `mysql`, `inventory`,
      `decluttering`

### Portfolio and search
- [ ] Update profile README, `portfolio.stephens.page`, `resume.stephens.page`
- [ ] Update `dashboard.stephens.page/stack` if it references the old name
- [ ] Submit new sitemap; configure canonical URLs
- [ ] Check social handles: `@keeplore` / `@keeploreapp` on X, Instagram,
      Threads, YouTube — lock the set in one pass
- [ ] Set bundle ID `app.keeplore` if/when mobile ships

---

## Clearance status — OUTSTANDING

**This document records preliminary distinctiveness from web search only. It is
not legal clearance.**

Before any branding spend beyond the domain:

- [ ] **USPTO Trademark Search**, classes **9** and **42** for KEEPLORE
      (Trademark Search replaced TESS in November 2023)
- [ ] Relevant state trademark records
- [ ] Apple App Store name search
- [ ] Google Play name search
- [ ] npm / PyPI / crates.io
- [ ] Plain Google for `"keeplore" app`
- [ ] EUIPO if international distribution ever matters

Estimated time: twenty minutes. Domains at standard price are cheap; rebranding
after app-store presence is not.

---

## Naming rules going forward

Derived from the pattern across Chart35, Quadrille, Caeven, Verelle, Nightloch,
Drome, and now Keeplore:

1. Coined or oblique, never a category noun.
2. Two to three syllables, soft consonants, no double-letter domain seam.
3. Bare `.app` or `.com` at standard price — no hyphens, no `-app` suffix
   domains.
4. Must survive the say-it-aloud test without spelling clarification.
5. **Must not compete with an established franchise, property, or company for
   its own search results. Ownability of the search result is the operative
   test — not merely trademark availability.** This is the rule that killed
   Stewardry and Keepward, and it should be applied in round one, not round
   three.
6. The tagline carries the description; the name carries the distinctiveness.
7. Clear before you buy branding; the domain itself is cheap enough to buy on
   conviction.