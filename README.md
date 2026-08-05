# WebIntelli

Test WordPress site for WP Engine Intelligent Web Tools — a dummy coffee information site used for customer demos.

## Repository layout

```
webintelli/              # the theme (this is the only thing deployed)
.github/workflows/       # deploy pipeline
```

## Auto-deployment

Pushes to `main` deploy the `webintelli/` directory into
`wp-content/themes/webintelli/` on the WP Engine install `webintell`, via
`wpengine/github-action-wpe-site-deploy`. `SRC_PATH` scopes the rsync to the
theme folder, so nothing else in this repo reaches the server.

Deploys are non-destructive by default — files deleted here are not removed
from the server. To change that, set `FLAGS` explicitly in the workflow:

```yaml
FLAGS: "-azvr --inplace --delete --exclude=.*"
```

## Theme

Classic PHP theme. No build step, no external font or JS dependencies.

| Content type | Archive | Single template |
| --- | --- | --- |
| Coffee Shops | `/coffee-shops/` | `single-coffee-shop.php` |
| Brewing Guides | `/brewing-guides/` | `single-brewing-guide.php` |
| Coffee Glossary | `/coffee-glossary/` (A–Z index) | `single-coffee-glossary.php` |

### Taxonomies

The ACF export defines no taxonomies, so the theme registers three:

| Taxonomy | Slug | Applies to | Hierarchical |
| --- | --- | --- | --- |
| Region | `/region/` | Coffee Shops | Yes |
| Brew Method | `/method/` | Brewing Guides, Glossary | No |
| Origin | `/origin/` | Brewing Guides, Posts | No |

All three render through `taxonomy.php`.

### ACF notes

Two field groups in `acf-export-2026-08-05.json` are bound to `post_type ==
post` rather than to their own post types:

- `group_6a4fb33e1abfe` "Brewing Guide Details" → should be `brewing-guide`
- `group_6a4fb53562911` "Glossary Term Details" → should be `coffee-glossary`

`inc/acf.php` corrects this at runtime via `acf/load_field_group`, so the
export can be imported as-is. Fixing the location rules in wp-admin and
re-exporting would let that filter be removed.

The export also sets `has_archive: false` on all three post types;
`inc/post-types.php` re-enables archives through `register_post_type_args`.

### Structured data

The AEO fields (`key_takeaway`, `key_facts_summary`, and the FAQ repeater) are
emitted as JSON-LD in `inc/schema.php`:

- Coffee shops → `CafeOrCoffeeShop`
- Brewing guides → `HowTo`
- Glossary terms → `DefinedTerm`
- Any post with FAQ rows → `FAQPage`

## Setup on a fresh install

1. Activate the theme (this flushes rewrite rules so archives resolve).
2. Import `acf-export-2026-08-05.json` via ACF → Tools.
3. Set Settings → Reading → homepage to a static page to get `front-page.php`.
4. Create a Primary menu, or let the built-in fallback list the three archives.

## Environment

- **WP Engine Install**: webintell
- **Theme Location**: wp-content/themes/webintelli/
- **Requires**: WordPress 6.4+, PHP 8.0+, ACF (Pro for the FAQ repeater)
