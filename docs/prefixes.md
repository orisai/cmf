## Prefixes

Some parts of the code share the same global scope, and so they have pretty high chance of running into a collision. For
purpose of prevention fo these collisions Orisai CMF defines common rules for code prefixing:

| Package        | Namespace      | Postgres schema | [Generic](#generic)  | Note                                                           |
|----------------|----------------|-----------------|----------------------|----------------------------------------------------------------|
| vendor/package | Vendor/Package | vendor_package  | vendor.package.<key> | Generic rules for any package                                  |
| orisai/*       | Orisai/*       | orisai_*        | orisai.*.<key>       | Same as generic vendor/package rules                           |
| orisai/nette-* | OriNette/*     | orisai_*        | orisai.*.<key>       | Nette extensions share same prefixes with their parent package |
| orisai/cmf     | OriCMF         | ori_cmf         | ori.cmf.<key>        | CMF                                                            |
| orisai/ext-*   | OriExt/*       | ori_*           | ori.*.<key>          | CMF extensions                                                 |

### Generic

These all follow the same rules:

- SQL migration groups - `vendor.package.<group>`
- DI extensions - `vendor.package.<extension>`
- DI services - `vendor.package.<a.b.c>`
- auth privileges - `vendor.package.<a.b.c>`
- translation keys - `vendor.package.<a.b.c>`

### Unprefixed

Prefixes would affect UX:

- HTTP routes
- CLI commands

Not used as often, prefixes are not needed:

- Latte macros
- Probably others
