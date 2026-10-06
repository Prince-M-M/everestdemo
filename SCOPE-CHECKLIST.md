# Scope of work: feature checklist

Checked against Munmat Technologies' *Digital Marketing, Website and Lead Management Strategy* (23 September 2026, revised).
Automated end-to-end test: `tests/e2e_scope_check.py` — **130 of 130 checks passed**, plus `tests/e2e_reminders_whatsapp_calls.py`: **44 of 44 passed** (174 in total).
(On a brand-new database, the follow-up alert check waits until a lead is older than the follow-up time.)

## Database updates (existing installs, run once each, in order)
1. `config/migrations/2026-10-02-channel-tracking.sql`
2. `config/migrations/2026-10-03-content-and-crud.sql`
3. `config/migrations/2026-10-04-reminders-whatsapp-calls.sql`
4. Optional: `config/migrations/2026-10-05-notification-email.sql` (sends every branch's new-lead alerts to pmataruse95@gmail.com)

New installs: import `config/schema.sql` only.

## Pillar A: Website
| Scope item | Where | Status |
|---|---|---|
| Mobile-first, fast | All pages, self-hosted fonts | Done |
| Apply Now in main navigation | Header, always visible on phones | Done |
| WhatsApp button on every page, pre-filled message | Floating button, header, hero; via `wa.php` | Done |
| Get a Quote form (low friction) | `quote.php` | Done |
| Service packages with indicative pricing | `services.php`, `plan.php` | Done (funeral service prices: set in `includes/site-data.php` when confirmed) |
| Staff and licensing credibility page | `team.php` | Done (profiles managed in dashboard; credentials numbers to fill in) |
| Obituary and tribute wall: families publish notices, receive condolences | `tributes.php`, `notice.php` | Done (moderated) |
| Testimonials | Home page | Done (managed in dashboard) |
| Sitemap, Search Console, structured data | `sitemap.php`, `robots.txt`, Settings, FuneralHome schema | Done |
| Source tagging at point of capture | `classes/Attribution.php` | Done |

## Pillar A2: Administrator dashboard
| Scope item | Status |
|---|---|
| Unified lead inbox (WhatsApp, quotes, applications) | Done. WhatsApp chats arrive automatically once the WhatsApp Business Platform is connected (reply from the lead page); until then staff log them in one step |
| New lead alert | Done: email + live on-screen alert and tab counter |
| Stages: new, contacted, application in progress, documents outstanding, converted | Done |
| Follow-up alerts after a set time | Done; time set in Settings |
| Staff assignment, by staff member or branch | Done (branch transfer: Head Office); also from the inbox, one lead or many at once |
| Application and document tracking: received vs outstanding | Done: checklist per application, uploads tick items off |
| Reporting by source: leads, applications, conversions | Done, plus CSV export |
| Role-based access | Done: branch staff see only their branch |

## Pillar C: Reputation and content
| Scope item | Status |
|---|---|
| Review requests after services | Done: WhatsApp or email from converted leads; dashboard queue |
| Content library (first 24 hours, burial vs cremation...) | Done: Guides CMS; 3 drafts to review and publish |
| Community content | Done: "Community" article type |
| Birthday and renewal reminders (new client onboarded) | Done: date of birth and consent on the application, policy details on the lead, reminders page, optional daily automatic emails |
| Call and WhatsApp tracking on all channels | Done: WhatsApp and phone-button taps counted by channel in Reports |

## CRUD added in this round
Leads (edit, move branch, delete), application documents (checklist add/tick/remove, file delete), staff (edit, reset password),
branches (delete when unused), notices and condolences, testimonials, team profiles with photos, guides and articles, settings.

## Outside the website build (ongoing services in the scope)
Google Business Profile, social media content, paid ads management and review monitoring are run by Munmat outside this codebase.
Automatic WhatsApp capture needs Everest's number connected to Meta's WhatsApp Business Platform (paid per conversation by Meta); the code, webhook and setup steps are ready (README).
Call tracking counts taps on phone numbers; recordings or call durations would need a call-tracking number service.


## Lead inbox update (2026-10-06)

Create, edit, change stage, assign and delete leads from `admin/leads.php`, with a progress bar and documents count per lead. Tested by `tests/e2e_leads_inbox.py` (46 checks).
