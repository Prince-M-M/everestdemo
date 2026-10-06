# Everest Funerals — Lead Management System

A real, working implementation of the Administrator Dashboard described in the
Munmat Technologies strategy document (Pillar A2), built to the confirmed spec:
6 branches, fully isolated data, a Head Office cross-branch role, real-time
email notifications, and one-click WhatsApp contact at zero ongoing cost.

## Deploying on Afrihost (cPanel shared hosting)

1. Upload everything to your hosting account. Only the `public/` folder's
   *contents that need to be reachable* should sit under `public_html` — the
   simplest safe approach on shared hosting is to put the whole app one level
   above `public_html`, and only symlink or copy `public/enquire.php` and
   `admin/`, `assets/`, `api/` into `public_html`, OR (simpler, and what most
   small agencies actually do on shared hosting) put the entire folder inside
   `public_html` as-is. The `.htaccess` files in `config/`, `classes/`, and
   `storage/` already block direct access to those folders either way, and
   uploaded documents live in `storage/uploads/`, entirely outside anything
   a URL can reach.
2. Create a MySQL database and user in cPanel, then import `config/schema.sql`
   via phpMyAdmin (cPanel → phpMyAdmin → your database → Import tab — no
   command line needed for this step).
3. Copy `.env.example` to `.env` and fill in your real database credentials
   and site URL. **Never commit the real `.env` file anywhere public.**
4. Visit `https://yourdomain/admin/setup.php` in your browser. This creates
   your 6 branches and your first Head Office login entirely through the
   browser — **no SSH or terminal access needed at all**, since that isn't
   guaranteed to be available on every shared hosting plan. The page checks
   whether any account already exists on every single request, both to view
   it and to submit it, so it permanently and automatically disables itself
   the moment your first account is created. It cannot be run a second time,
   even by someone who later finds the URL.
5. Confirm PHP's `sendmail_path` is set on your Afrihost account (it is by
   default on cPanel) so real-time email notifications actually deliver —
   test this immediately using the "Log a lead" screen with your own email
   address before relying on it for a real enquiry.
6. Point each branch's public enquiry page at your main website:
   `https://yourdomain/public/enquire.php?branch=<branch-slug>` — each
   branch's exact link is shown on the Branches page (Head Office only).
   `setup.php` creates all 6 branches with placeholder contact details and
   your own email as a temporary stand-in notification address for every
   branch — update each branch's real phone, WhatsApp number, and
   notification email on the Branches page as your very next step after
   logging in for the first time.

### If you already ran `php scripts/seed.php` via SSH successfully
You don't need `setup.php` — it will correctly detect the accounts that
script created and refuse to run, since its only job is to safely cover the
case where step 4 above isn't possible. Either path arrives at the same
working system.

## The public website (Pillar A)

Eleven pages live at the project root, built from real content pulled
directly from the live site at the time of this build (mission, vision,
testimonials, plan names, trust badges, the Pretoria brand-protection
notice) and using your actual logo and its exact sampled brand colours
(navy `#062A4E`, gold `#DEAE36`).

| Page | File |
|---|---|
| Home | `index.php` |
| About Us | `about.php` |
| Our Services (all 6 plans) | `services.php` |
| Individual plan page | `plan.php?slug=care-plan` (and 5 others) |
| Claim Process | `claim-process.php` |
| Branches | `branches.php` |
| Tribute Wall | `tributes.php` |
| Pre-Need Planning | `pre-need.php` |
| Contact Us | `contact.php` |
| Apply Now | `apply.php` |
| Terms & Conditions | `terms.php` |

**Every form on every page (Contact, Apply Now, each plan's quote form,
Pre-Need) creates a real lead in the exact same database the dashboard
reads from** — confirmed by direct testing: a lead submitted through the
live Contact form correctly appeared in the right branch's dashboard,
correctly triggered the real-time notification flow, and correctly stayed
invisible to a different branch's staff. This isn't two separate systems
that need to be kept in sync; it's one system with a public front door and
a staff-facing back office.

### Content that is a placeholder, clearly marked, and must be replaced
Two things could not be built from real information because it wasn't
available, and are flagged with an on-page notice wherever they appear so
nobody mistakes them for confirmed fact:
- **The exact inclusions of each of the 6 plans** — only the plan names
  and a generic teaser line were visible on the live site's homepage.
  `includes/site-data.php` has an indicative starting structure for each
  plan; replace it with Everest's real, current policy wording.
- **The Claim Process steps** — a reasonable, typical outline, not
  confirmed against Everest's actual process or required documents.
- **Terms & Conditions** (`terms.php`) — entirely a placeholder notice.
  The Apply Now form links to this page and asks applicants to confirm
  they've read it, so this must be the real, legally reviewed document
  before the site accepts a single real application.

### The Tribute Wall is moderated, not automatic
A submission on `tributes.php` is saved as "pending" and does not appear
publicly until a Head Office user approves it from `admin/tributes.php` —
confirmed by direct testing, including that it is genuinely invisible to
the public page until approved. This prevents anything inappropriate or
spam ever appearing unmoderated on a page about grieving families.


**For a real deployment:** use `admin/setup.php` as described above — that's
the account you'll actually use.

**For exploring this delivery right now**, `scripts/seed.php` (which requires
being run via command line, so only works if you have shell/SSH access) also
creates 4 test accounts across all three roles, useful for confirming things
like branch isolation actually work before you go live:

| Role | Email | Password |
|---|---|---|
| Head Office | `ho@everestfunerals.test` | `HeadOffice#2026` |
| Branch Manager (Rustenburg North) | `manager.rn@everestfunerals.test` | `Manager#2026` |
| Branch Staff (Rustenburg North) | `staff.rn@everestfunerals.test` | `Staff#2026Pass` |
| Branch Staff (Thabazimbi) | `staff.thaba@everestfunerals.test` | `Staff#2026Pass` |

Delete `scripts/seed.php` from the server once you've either used it or
decided to use `setup.php` instead — like `setup.php`, it's a setup
convenience, not something that should still exist once real people are
using this day to day.

## What "real-time" means here, precisely

- **Email (admin and customer):** fires synchronously the instant a lead is
  created or its status changes to something customer-facing. There is no
  queue, no delay, no cron job in between — the send happens in the same
  request that created the lead.
- **WhatsApp:** Meta charges for any WhatsApp message a business sends
  proactively (i.e. not a reply to a message the customer sent first via
  WhatsApp itself) — confirmed directly against Meta's own current pricing
  documentation, and there is no free tier that covers this specific case.
  Rather than either pay per message or silently break that promise, every
  new lead shows an instant, pre-filled "WhatsApp this lead" button the
  moment it appears on the dashboard. Staff click once and it opens WhatsApp
  with the right number and message ready to send — zero cost, zero Meta
  account setup, and it can never violate WhatsApp's terms of service. The
  click is logged to the lead's activity timeline.

## Branch isolation, precisely

Every branch-scoped page checks the record's actual `branch_id` against the
logged-in user's session `branch_id` on the server, via `Auth::assertBranchAccess()`
— never by hiding a link in the UI. A branch_staff or branch_manager account
that tries to view another branch's lead by editing the URL gets a 403 with
no data shown, confirmed by direct testing, not just code review. Head Office
accounts have `branch_id = NULL` and can see everything, exactly as agreed.

## Security measures, precisely

Each of these was verified against a running instance of this exact code,
not assumed from the source:

- **SQL injection:** every database query goes through PDO prepared
  statements with parameters bound separately from the SQL text — confirmed
  by submitting classic `' OR '1'='1`, UNION-based, and destructive
  `DROP TABLE` payloads through both the login form and the lead search box.
  All were treated as inert literal text; the database was untouched.
- **CSRF:** every state-changing form requires a token tied to the session.
  Submitting a status change without one is rejected and the person is sent
  back to a working page with a clear message, rather than a dead-end error
  screen or, worse, silently succeeding.
- **Privilege escalation:** a branch manager attempting to create a Head
  Office account (by editing the submitted form fields directly, bypassing
  the UI) is rejected server-side with a clear error. Role and branch
  restrictions are enforced in PHP on every request, never trusted from a
  hidden form field.
- **File uploads:** both the file extension and the file's actual content
  (via PHP's `finfo`, not the browser-supplied MIME type) must agree before
  a case document is accepted. A PHP script renamed to `.pdf` was tested
  directly and rejected; nothing was written to disk. Accepted files are
  stored under a random filename outside the web root, so even a
  hypothetical future bypass could not be requested and executed by URL.
- **Passwords:** hashed with PHP's `password_hash()` (bcrypt), never stored
  or logged in plain text. Login attempts are rate-limited per email and per
  IP address, with a 15-minute lockout after 5 failures.
- **Sessions:** httponly, samesite cookies; regenerated on login to prevent
  fixation; an 8-hour idle timeout enforced on every request, not only at
  login.

## Project structure

| Path | Purpose |
|---|---|
| `config/` | Bootstrap, environment, database credentials (`.htaccess`-protected) |
| `classes/` | Domain logic: Auth, Database, Lead, LeadCase, Branch, User, Notifier, Upload (`.htaccess`-protected) |
| `admin/` | The staff-facing dashboard (all pages require login) |
| `public/` | The one page with no login required: the enquiry form |
| `includes/emails/` | HTML email templates |
| `storage/` | Sessions, uploaded documents, logs — outside any URL's reach |
| `scripts/seed.php` | One-time setup helper; delete after real branches/staff exist |

## Known limitation, stated plainly

This was built and thoroughly tested in a local development sandbox against
MariaDB and PHP's built-in server, standing in for Afrihost's actual cPanel
environment (confirmed compatible: PHP 8.1+, MySQL, cron jobs). Email
sending itself could not be tested against a real mail server in this
sandbox (there is no mail transport installed here) — the send logic was
verified correct by substituting a working local mail handler and confirming
the system correctly logs success and failure either way, but the very last
step, actual delivery through Afrihost's real mail server, should be
confirmed with a real test enquiry immediately after deployment.

## A real bug found and fixed during testing

Real-browser testing at multiple mobile widths caught a genuine layout bug
on `contact.php`: at 375px it overflowed sideways by 45px, growing to 100px
at 320px. First suspicion (the off-canvas mobile nav) turned out to be a
red herring, confirmed by disabling the nav entirely and watching the
overflow stay exactly the same. The real cause was a set of inline-styled
label/value rows (Phone, Email, Address) where a fixed-width label plus an
unbreakable string, a phone number, an email address, defaults to forcing
the row, and everything containing it, wider than the screen. Fixed by
replacing the inline styles with a proper `.info-row` CSS class that
explicitly allows the value side to shrink and wrap. Re-tested afterward
at six widths from 320px to 768px, all clean, and a full site-wide sweep
across every page confirmed nothing else was affected.

## A second real bug found and fixed: CSS and links not working at all

After delivery, the homepage and other root-level pages (`index.php`,
`contact.php`, etc.) were reported as not loading CSS and not navigating
correctly, while the admin dashboard worked fine. Traced to a real,
significant bug: the code that works out the site's own base URL was
computing it by climbing a fixed number of directory levels up from
whichever script was currently running. That number was only ever correct
for pages one level deep (`admin/login.php`), which is all that existed
when it was first written. The moment root-level pages were added, sitting
at a different depth, every URL on those pages silently pointed to the
wrong location, and only actually broke once the site was deployed under a
subdirectory rather than a bare domain root — which meant it worked in
every sandbox test done during development and only showed up on the real
deployment.

Fixed properly: the base URL is now derived by comparing this project's own
fixed filesystem location against the server's document root, which gives
the same correct answer for every page regardless of its depth. Verified
directly, not just reasoned through: built an actual subdirectory
deployment (`/everest-leads/` under a separate web root, mirroring a real
shared-hosting layout) and confirmed both a root-level page and a nested
admin page now generate identical, correct, working URLs, that the CSS
file itself loads and visibly applies, and that clicking a real link
navigates correctly within the subdirectory. Then re-confirmed the original
domain-root scenario still works exactly as before, so neither deployment
style was broken by fixing the other.

**If this ever happens again on your actual server** (a misconfigured
DOCUMENT_ROOT, an unusual hosting setup), there is a guaranteed, reliable
override: set `APP_URL` in your `.env` file to your exact site URL, for
example `APP_URL=https://leads.everestfunerals.co.za`, and every page will
use that value directly rather than trying to auto-detect it at all.



## Working leads from the inbox

Everything about a lead can now be done straight from **Lead inbox**, without opening each one:

- **Stage:** each row has a stage dropdown (New, Contacted, Application in progress, Documents outstanding, Converted, Lost). Changing it records the first response time, adds a timeline entry, emails the family if they gave an email address, and opens the application with its documents checklist once an application is under way.
- **Dealt with by:** each row has a dropdown of the active staff at that lead's own branch. A lead can never be given to someone at another branch, even by tampering with the page.
- **Progress:** a five-step bar shows how far along the journey the family is, plus "Documents 2 of 4" once an application is open (click it to go to the application).
- **Edit:** opens a quick edit window for name, phone, email, interested in, stage and handler. Date of birth, policy details and reminders are still edited on the lead's own page, and the quick edit never wipes them.
- **Delete:** Head Office only, with a confirmation, and recorded in the audit log.
- **Tick boxes:** tick several leads to move them to a stage, give them to a staff member, or (Head Office) delete them together. Leads that can't take the chosen staff member are skipped and the message says how many.
- **Shortcuts:** "My open leads", "Nobody dealing with it yet" and "Waiting on documents".
- **Add a lead** can now give the new lead to a staff member straight away, and "Interested in" suggests the plan and service names exactly as they appear on the website.

No database changes are needed for this update.

## Birthday and policy anniversary reminders

The application form asks for the main member's date of birth and an optional,
separate tick box agreeing to birthday and yearly policy messages (POPIA:
only clients who ticked it are ever included). Staff add the policy number,
start date and debit day on the lead page once the policy is active.

- **Dashboard > Birthdays and renewals** lists birthdays (next 14 days) and
  policy anniversaries (next 30 days) with one-tap WhatsApp or email.
- Each message is sent at most once a year per client.
- Automatic emails are switched ON (Settings). They need one daily cron
  job in cPanel > Cron Jobs, set up once when the site goes live:
  `0 8 * * * /usr/local/bin/php /home/<cpanel-user>/<site>/scripts/send-reminders.php`

## WhatsApp chats straight into the inbox (not used for now)

Everest is using normal WhatsApp for now, so this is switched off and hidden
in Settings; staff use "Log a WhatsApp chat". The steps below are kept in
case Everest connects a number later.

Without this, every WhatsApp button tap is counted and staff log chats with
"Log a WhatsApp chat". To make chats arrive automatically:

1. In Meta Business Manager, add Everest's number to the **WhatsApp Business
   Platform (Cloud API)** and create a Meta app. Note: a number used on the
   Cloud API can't also be used in the ordinary WhatsApp Business phone app,
   so many businesses use a second number for this.
2. Fill in the four `WHATSAPP_` lines in `.env` (verify token: any long random
   text; app secret: from the Meta app settings; a permanent system-user
   access token; the phone number ID).
3. In the Meta app, set the webhook URL to `https://<your domain>/wa-webhook.php`,
   enter the same verify token, and subscribe to **messages**.
4. Choose which branch receives new chats in Settings.

New chats create a lead (source WhatsApp, or Facebook/Instagram when the
family tapped a click-to-WhatsApp ad), alert the branch by email, and show as
a conversation on the lead page. Staff can reply from there within WhatsApp's
24-hour customer service window. Messages from an existing family are added
to their existing lead rather than creating duplicates. Meta charges per
conversation; see Meta's current WhatsApp pricing.

## Call tracking

Every tap on a phone number on the website is counted by channel and shown to
Head Office in Reports by source. This counts taps, not completed calls; for
call recordings and durations, a call-tracking number service would be needed.

## Security notes

`scripts/`, `tests/`, `includes/`, `config/`, `classes/` and `storage/` are
blocked from the web by .htaccess. `scripts/seed.php` creates test accounts and
also refuses to run from a browser; never run it on the live database.
