# Keeplore Logo / App Icon

## Source of truth
- **Raster master:** `keeplore-icon-master.png` (1024×1024, generated with inkvoke / gpt-image-2)
- **PWA / site:** `icon-512x512.png`, `icon-192x192.png`
- **Vector sketch:** `logo.svg` (geometric K + arc; refine if needed)

## Design
- Deep navy rounded square `#30395c`
- Cyan→teal gradient `#0093e9` → `#80d0c7`
- Bold geometric capital **K** (keep monogram)
- Thin circular tracking arc with glowing top dot (interact-by cycle)
- Flat, no shadows, no wordmark, no amphora / letter A

## inkvoke regenerate

```bash
inkvoke generate "$(cat <<'EOF'
App icon, square 1024, flat vector style (not photorealistic, not 3D, no soft shadows).

Subject: modern minimalist monogram for Keeplore, a personal possessions tracker.

Composition: rounded-square app icon (iOS-style continuous corner radius). Deep navy solid background exactly #30395c. Centered: a bold geometric capital letter K filled with a smooth cyan-to-teal vertical gradient (#0093e9 at top → #80d0c7 at bottom). The K is clean, slightly refined, solid weight, clearly readable at small sizes — like a keep/record mark, not a font dump.

Around the K: a thin circular arc (stroke ~2–3% of width) in the same cyan-teal gradient, nearly closed, with a small soft-glow cyan-teal dot sitting on the arc near the top (timer / interact-by cycle cue). Arc sits outside the K with comfortable padding; K stays fully inside the circle.

No amphora, no vase, no letter A, no words, no wordmark, no extra icons, no noise texture, no drop shadow. Premium digital-curator flat design. Centered, balanced, export-ready app icon.
EOF
)" \
  --output ui/assets/keeplore-icon-master.png \
  --size 1024x1024 \
  --quality high
```

Then resize:

```bash
sips -z 1024 1024 ui/assets/keeplore-icon-master.png --out ui/assets/icon-512x512.png
sips -z 192 192 ui/assets/keeplore-icon-master.png --out ui/assets/icon-192x192.png
```

## Note on inkvoke edit
As of inkvoke 1.0.1, `edit` can fail with `unsupported mimetype ('application/octet-stream')` because multipart form parts use Go’s default `CreateFormFile` Content-Type. Prefer `generate` until that is fixed, or patch uploads to set `image/png` / `image/jpeg` / `image/webp` explicitly.
