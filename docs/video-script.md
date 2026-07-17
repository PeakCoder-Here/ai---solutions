# AI-Solutions — Demo Video Script (10 minutes)

Record on the **C: drive copy** (`localhost/ai-solutions`) — it has live SMTP so email sends actually work on camera.
Two windows ready before you hit record: the public site, and an email inbox tab (mycorruptedmind7@gmail.com) so you can flip over and show a received email live.

---

## 0:00 – 0:40 — Intro (40s)

**SAY:**
"Hi, this is a demo of AI-Solutions — a full-stack website I built for [module/assignment name]. It's a PHP and MongoDB application with a public marketing site, a content management system for admins, a rule-based AI chatbot, and live transactional email automation. I'll walk through the customer-facing site first, then show the admin CMS, then prove the email automation actually sends real mail."

**DO:** Show the homepage (`index.php`) loaded, full width, no scrolling yet.

---

## 0:40 – 1:30 — Homepage & navigation (50s)

**SAY:**
"The homepage covers our value proposition, services, and social proof. Navigation up top links to every section: Solutions, Case Studies, Gallery, Events, Blog, Feedback, Contact, and Schedule Demo."

**DO:** Scroll homepage top to bottom at a steady pace, then click through the nav bar once quickly (don't linger — just prove each link works) before landing on Solutions.

---

## 1:30 – 2:20 — Solutions & Case Studies (50s)

**SAY:**
"Solutions covers our four core offerings — AI Virtual Assistant, Rapid Prototyping, Digital Transformation, and Custom AI Integration — each with real pricing.

Case Studies is fully CMS-driven — every card you see here, including the industry tag, challenge, solution text, and result stats, comes from a MongoDB collection I can manage from the admin panel, which I'll show shortly."

**DO:** Scroll Solutions briefly, click to Case Studies, scroll through the 3 cards.

---

## 2:20 – 3:10 — Gallery with lightbox (50s)

**SAY:**
"The gallery is also database-backed. Clicking any photo opens a custom lightbox I built — full image view, with next/previous navigation and keyboard support."

**DO:** Open Gallery, click a photo to open the lightbox, click **next** twice, press **Escape** to close.

---

## 3:10 – 4:20 — Events page: upcoming / ongoing / past (70s)

**SAY:**
"Events are categorized automatically by date — happening today, upcoming, and past — computed live against the server's current date, not hardcoded. Right now you can see [name the event you added for today] under 'Happening Today'. Clicking any card opens a full detail page with the venue, time, and description, all pulled from the same events collection the admin manages."

**DO:** Open Events, point out the three sections, click into one event's detail page, then back.

---

## 4:20 – 5:40 — AIVA chatbot (80s)

**SAY:**
"Every page has AIVA, a rule-based assistant. It's not calling an external AI API — it's a keyword-scoring engine I wrote that also queries live data for anything event-related, so its answers stay in sync with whatever's in the database."

**DO (type each into the chat widget, wait for the reply each time):**
1. `hi` → greeting response
2. `what services do you offer` → services list
3. `upcoming events` → shows only upcoming events
4. `any events happening today` → shows only today's event
5. `tell me about the ai summit` → live-looked-up specific event reply

**SAY (while replies come in):**
"Notice the upcoming and 'happening today' questions return completely different, specific lists — and the AI Summit question pulled its date, time, and location straight from the database in real time."

---

## 5:40 – 6:30 — Feedback submission (50s)

**SAY:**
"Customers can leave feedback with a star rating. New submissions go into a pending queue — they don't appear publicly until an admin approves them, which I'll demonstrate in the admin panel."

**DO:** Go to Feedback page, scroll approved testimonials briefly, then fill out and submit the feedback form with a short review and star rating.

---

## 6:30 – 7:40 — Contact form → live email proof (70s)

**SAY:**
"Now the part I want to prove is real: email automation. I'll submit the contact form with my own email address, and we should get a real email — not a simulated one — within a few seconds."

**DO:**
1. Go to Contact page, fill in name/email/company/job title/message with your real inbox address, submit.
2. Cut to the email inbox tab, refresh, show the new **"We've received your enquiry — AI-Solutions"** email arriving with the message content you typed.

**SAY:**
"That's a genuine SMTP send through Gmail — the form submission triggered a queued email job, which PHPMailer sent over real SMTP. An admin notification email fires at the same time, so the business also gets alerted to every enquiry."

---

## 7:40 – 9:20 — Admin panel walkthrough (100s)

**SAY:**
"Now let's look at the admin side, protected by login."

**DO:** Go to `/admin/login.php`, log in.

**SAY while navigating:**
"The dashboard gives an overview of activity. Under Content, I have full CMS control: Blog, Events, Case Studies, Gallery, and Feedback moderation — all CRUD, all backed by MongoDB."

**DO, quickly, one action each:**
1. **Manage Events** — click Add Event, fill a quick example, save, show it appear in the list with its Upcoming/Today/Past status column.
2. **Manage Case Studies** — open the edit form for one entry to show the stat fields are structured, not free text.
3. **Manage Feedback** — go to the Pending tab, find the review you just submitted, click **Approve**.
4. Flip back to the public Feedback page, refresh, show the new testimonial now live.

**SAY:**
"Under Submissions, I can see every Order, Demo Request, Contact Message, and Event Registration that's come through the site — including the contact enquiry I just submitted a minute ago."

**DO:** Click into Contact Messages or Orders briefly to show the new row.

---

## 9:20 – 9:50 — Tech stack recap (30s)

**SAY:**
"Under the hood: PHP 8 for the backend, MongoDB for all data storage, PHPMailer over real Gmail SMTP for transactional email, and a hand-rolled JavaScript rule engine for the chatbot that queries the database server-side and injects live data into the client. Everything you saw — events, case studies, gallery, feedback — is fully CMS-managed, not hardcoded."

**DO:** Optional: quick flash of the project file structure in an editor, if you want to show code organization.

---

## 9:50 – 10:00 — Closing (10s)

**SAY:**
"That's the full AI-Solutions platform — public site, admin CMS, live chatbot, and working email automation, all demonstrated live. Thanks for watching."

**DO:** Cut back to homepage or a clean final shot.

---

## Notes for recording

- Practice the chatbot section once beforehand — typing live under time pressure is the easiest part to fumble.
- If the email arrives slower than expected on camera, say "sending now, checking inbox" and cut the clip shorter in editing rather than sitting in dead air.
- Keep mouse movements deliberate and not too fast — makes it easier to follow and looks more confident.
- Total run time above is ~10:00; if you're consistently going over, trim the admin panel section first (it has the most sub-steps) rather than cutting the email proof, which is your strongest "this actually works" moment.
