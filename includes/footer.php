<?php $base = $base ?? ''; ?>
</main>
<footer class="site-footer">
    <div class="container footer__grid">
        <div class="footer__col">
            <div class="footer__logo"><span class="logo__icon"><i class="fa fa-robot"></i></span><span class="logo__text">AI<span class="logo__accent">-Solutions</span></span></div>
            <p class="footer__tagline">Innovate. Promote. Deliver.<br>The Future of Digital Employee Experience.</p>
            <div class="footer__social">
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
        </div>
        <div class="footer__col">
            <h3 class="footer__heading">Quick Links</h3>
            <ul class="footer__links">
                <li><a href="<?= $base ?>index.php">Home</a></li>
                <li><a href="<?= $base ?>solutions.php">Software Solutions</a></li>
                <li><a href="<?= $base ?>case-studies.php">Case Studies</a></li>
                <li><a href="<?= $base ?>gallery.php">Gallery</a></li>
                <li><a href="<?= $base ?>events.php">Upcoming Events</a></li>
                <li><a href="<?= $base ?>blog.php">Blog</a></li>
            </ul>
        </div>
        <div class="footer__col">
            <h3 class="footer__heading">Get Involved</h3>
            <ul class="footer__links">
                <li><a href="<?= $base ?>feedback.php">Customer Feedback</a></li>
                <li><a href="<?= $base ?>schedule-demo.php">Schedule a Demo</a></li>
                <li><a href="<?= $base ?>contact.php">Contact Us</a></li>
                <li><a href="<?= $base ?>join-events.php">Join Our Events</a></li>
            </ul>
        </div>
        <div class="footer__col">
            <h3 class="footer__heading">Contact</h3>
            <address class="footer__address">
                <p><i class="fa fa-map-marker-alt"></i> Sunderland, UK</p>
                <p><i class="fa fa-envelope"></i> info@ai-solutions.co.uk</p>
                <p><i class="fa fa-phone"></i> +44 191 000 0000</p>
            </address>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container footer__bottom-inner">
            <p>&copy; <?= date('Y') ?> AI-Solutions. All rights reserved.</p>
            <p><a href="<?= $base ?>admin/login.php" class="footer__admin-link"><i class="fa fa-lock"></i> Staff Login</a></p>
        </div>
    </div>
</footer>
<script src="<?= $base ?>js/main.js"></script>
<?php if (($currentPage ?? '') === 'gallery'): ?>
<script src="<?= $base ?>js/lightbox.js"></script>
<?php endif; ?>
<style>
/* ── Chatbot FAB ──────────────────────────────────────────── */
@keyframes chatPulse{0%,100%{box-shadow:0 0 0 0 rgba(79,70,229,.5),0 8px 28px rgba(79,70,229,.45)}70%{box-shadow:0 0 0 14px rgba(79,70,229,0),0 8px 28px rgba(79,70,229,.45)}}
@keyframes chatBounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}

#ab-wrap{position:fixed;bottom:1.75rem;right:1.75rem;z-index:9999;display:flex;flex-direction:column;align-items:flex-end;gap:.5rem;}

#ab-tip{background:#1E1B4B;color:#fff;font-family:'Inter',sans-serif;font-size:.78rem;font-weight:600;padding:.3rem .75rem;border-radius:20px;white-space:nowrap;opacity:0;transform:translateY(4px) scale(.95);transition:opacity .2s ease,transform .2s ease;pointer-events:none;box-shadow:0 2px 10px rgba(0,0,0,.15);}
#ab-wrap:hover #ab-tip{opacity:1;transform:translateY(0) scale(1);}

#ab{position:relative;width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#4F46E5 0%,#7C3AED 100%);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;animation:chatPulse 2.5s ease-in-out infinite,chatBounce 3s ease-in-out infinite;transition:transform .2s ease,filter .2s ease;flex-shrink:0;}
#ab:hover{transform:scale(1.1)!important;filter:brightness(1.1);animation:none;box-shadow:0 8px 28px rgba(79,70,229,.55);}
#ab i{font-size:1.65rem;color:#fff;pointer-events:none;}

/* notification dot */
#ab::after{content:'';position:absolute;top:4px;right:4px;width:12px;height:12px;border-radius:50%;background:#10B981;border:2px solid #fff;}

/* ── Chat Window ──────────────────────────────────────────── */
#aw{position:fixed;bottom:5.5rem;right:1.75rem;z-index:9999;width:350px;background:#fff;border-radius:20px;box-shadow:0 16px 48px rgba(16,24,40,.18),0 2px 8px rgba(16,24,40,.08);display:none;flex-direction:column;overflow:hidden;border:1px solid #E2E8F0;}
#aw.on{display:flex;}
#ah{background:linear-gradient(135deg,#4F46E5,#7C3AED);padding:1rem 1.1rem;color:#fff;font-family:'Space Grotesk','Inter',sans-serif;font-weight:700;font-size:1rem;display:flex;justify-content:space-between;align-items:center;letter-spacing:-.01em;}
#ah span:first-child{display:flex;align-items:center;gap:.5rem;}
#am{height:300px;overflow-y:auto;padding:.9rem;background:#F8FAFC;display:flex;flex-direction:column;gap:.6rem;}
.bm{background:#fff;padding:.6rem .85rem;border-radius:14px 14px 14px 4px;font-family:'Inter',sans-serif;font-size:.85rem;line-height:1.5;box-shadow:0 1px 4px rgba(0,0,0,.07);max-width:85%;align-self:flex-start;color:#1E293B;}
.um{background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;padding:.6rem .85rem;border-radius:14px 14px 4px 14px;font-family:'Inter',sans-serif;font-size:.85rem;line-height:1.5;max-width:85%;align-self:flex-end;}
#ai{display:flex;padding:.65rem;border-top:1px solid #E2E8F0;gap:.5rem;background:#fff;}
#ai input{flex:1;border:1.5px solid #E2E8F0;border-radius:20px;padding:.5rem .9rem;font-family:'Inter',sans-serif;font-size:.85rem;outline:none;color:#1E293B;transition:border-color .2s;}
#ai input:focus{border-color:#4F46E5;}
#ai button{background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;border:none;border-radius:50%;width:36px;height:36px;cursor:pointer;font-size:.85rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:filter .2s;}
#ai button:hover{filter:brightness(1.15);}

.aiva-chips{display:flex;flex-wrap:wrap;gap:.35rem;padding:.15rem 0 .25rem;}
.aiva-chips button{background:#EEF2FF;color:#4F46E5;border:1px solid #C7D2FE;border-radius:20px;padding:.28rem .7rem;font-size:.77rem;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:background .15s,color .15s;}
.aiva-chips button:hover{background:#4F46E5;color:#fff;}
.typing-ind{display:flex;gap:5px;align-items:center;padding:.45rem .85rem!important;}
.typing-ind span{width:7px;height:7px;background:#CBD5E1;border-radius:50%;display:block;animation:tdot .9s ease-in-out infinite;}
.typing-ind span:nth-child(2){animation-delay:.15s;}
.typing-ind span:nth-child(3){animation-delay:.3s;}
@keyframes tdot{0%,60%,100%{transform:translateY(0)}30%{transform:translateY(-5px)}}
@media (max-width: 480px) {
    #aw { right: 0.75rem; left: 0.75rem; width: auto; }
    #ab-wrap { bottom: 1rem; right: 1rem; }
}
</style>

<div id="ab-wrap">
  <span id="ab-tip">Chat with AIVA</span>
  <button id="ab" onclick="document.getElementById('aw').classList.toggle('on')" aria-label="Open chat with AIVA"><i class="fa fa-robot"></i></button>
</div>
<div id="aw">
  <div id="ah"><span><i class="fa fa-robot"></i> AIVA &mdash; AI-Solutions</span><span onclick="document.getElementById('aw').classList.remove('on')" style="cursor:pointer;font-weight:400;opacity:.8;" aria-label="Close chat">&#10005;</span></div>
  <div id="am">
    <div class="bm">Hi! &#128075; I'm AIVA. Ask me anything about AI-Solutions — services, pricing, events, gallery, blog, or contact info!</div>
    <div id="aiva-chips" class="aiva-chips">
      <button onclick="askAiva('Our services')">Our services</button>
      <button onclick="askAiva('Pricing plans')">Pricing</button>
      <button onclick="askAiva('Upcoming events')">Events</button>
      <button onclick="askAiva('Book a demo')">Book demo</button>
      <button onclick="askAiva('Contact us')">Contact</button>
    </div>
  </div>
  <div id="ai"><input id="at" placeholder="Type a message..." onkeydown="if(event.key==='Enter'){event.preventDefault();sendA();}"><button onclick="sendA()"><i class="fa fa-paper-plane"></i></button></div>
</div>
<?php
// Live event data for AIVA — bucketed by date so the chatbot can answer
// "upcoming", "ongoing/today" and "past" questions with only that subset.
require_once __DIR__ . '/db.php';
$__aivaOngoing = []; $__aivaUpcoming = []; $__aivaPast = []; $__aivaAll = [];
$__aivaToday = new DateTime('today');
foreach ($db->events->find([], ['sort' => ['sort_order' => 1]]) as $__ev) {
    $__day = (int)($__ev['day'] ?? 0); $__month = (string)($__ev['month'] ?? '');
    $__d = ($__day && $__month) ? DateTime::createFromFormat('j M Y', "{$__day} {$__month} " . $__aivaToday->format('Y')) : false;
    $__line = htmlspecialchars($__ev['title']) . ' — ' . htmlspecialchars(trim(($__ev['day'] ?? '') . ' ' . ($__ev['month'] ?? ''))) . (!empty($__ev['location']) ? ', ' . htmlspecialchars($__ev['location']) : '');
    if ($__d === false) { $__aivaUpcoming[] = $__line; }
    elseif ($__d->format('Y-m-d') === $__aivaToday->format('Y-m-d')) { $__aivaOngoing[] = $__line; }
    elseif ($__d > $__aivaToday) { $__aivaUpcoming[] = $__line; }
    else { $__aivaPast[] = $__line; }
    $__aivaAll[] = (array) $__ev;
}
function aiva_event_list(array $lines, string $emptyMsg): string {
    if (empty($lines)) return $emptyMsg;
    return implode('<br>', array_map(fn($l) => '&bull; ' . $l, $lines));
}
$__aivaOngoingMsg  = '&#128204; <b>Happening today:</b><br>' . aiva_event_list($__aivaOngoing, 'Nothing is running today. Check our Events page for what\'s coming up!');
$__aivaUpcomingMsg = '&#128197; <b>Upcoming events:</b><br>' . aiva_event_list($__aivaUpcoming, 'No upcoming events scheduled right now — check back soon!') . '<br>Visit our <b>Events page</b> to register!';
$__aivaPastMsg     = '&#128197; <b>Past events:</b><br>' . aiva_event_list($__aivaPast, 'No past events on record yet.');

// Look up ONE specific event by keyword match against its title, and format
// a live reply from real fields — so chatbot answers stay in sync with
// whatever is actually in the Events CMS, with no hardcoded per-event text.
function aiva_find_event(array $events, array $keywords): ?array {
    foreach ($events as $ev) {
        $title = strtolower((string)($ev['title'] ?? ''));
        foreach ($keywords as $kw) {
            if (str_contains($title, $kw)) return $ev;
        }
    }
    return null;
}
function aiva_event_blurb(?array $ev, string $emoji, string $notFoundMsg): string {
    if (!$ev) return $notFoundMsg;
    $when = trim(($ev['day'] ?? '') . ' ' . ($ev['month'] ?? ''));
    $bits = [];
    if ($when) $bits[] = htmlspecialchars($when);
    if (!empty($ev['time'])) $bits[] = htmlspecialchars($ev['time']);
    if (!empty($ev['location'])) $bits[] = htmlspecialchars($ev['location']);
    $meta = implode(' &middot; ', $bits);
    $desc = !empty($ev['description']) ? ' ' . htmlspecialchars($ev['description']) : '';
    return $emoji . ' <b>' . htmlspecialchars($ev['title']) . '</b> &mdash; ' . $meta . '.' . $desc . ' Register on our <b>Events page</b>!';
}
$__aivaSummitMsg   = aiva_event_blurb(aiva_find_event($__aivaAll, ['summit']),               '&#127965;&#65039;', 'We don\'t have a Summit event listed right now — ask me about <b>upcoming events</b> to see what\'s on!');
$__aivaWebinarMsg  = aiva_event_blurb(aiva_find_event($__aivaAll, ['webinar']),               '&#128187;',          'No webinars scheduled right now — ask me about <b>upcoming events</b> to see what\'s on!');
$__aivaMeetupMsg   = aiva_event_blurb(aiva_find_event($__aivaAll, ['meetup', 'tech meetup']), '&#129309;',          'No meetups scheduled right now — ask me about <b>upcoming events</b> to see what\'s on!');
$__aivaWorkshopMsg = aiva_event_blurb(aiva_find_event($__aivaAll, ['workshop', 'sprint']),    '&#9889;',            'No workshops scheduled right now — ask me about <b>upcoming events</b> to see what\'s on!');
$__aivaStemMsg     = aiva_event_blurb(aiva_find_event($__aivaAll, ['stem', 'outreach', 'schools']), '&#127891;',    'No schools outreach events scheduled right now — ask me about <b>upcoming events</b> to see what\'s on!');
?>
<script>
var AIVA_ONGOING  = <?= json_encode($__aivaOngoingMsg) ?>;
var AIVA_UPCOMING = <?= json_encode($__aivaUpcomingMsg) ?>;
var AIVA_PAST     = <?= json_encode($__aivaPastMsg) ?>;
var AIVA_SUMMIT   = <?= json_encode($__aivaSummitMsg) ?>;
var AIVA_WEBINAR  = <?= json_encode($__aivaWebinarMsg) ?>;
var AIVA_MEETUP   = <?= json_encode($__aivaMeetupMsg) ?>;
var AIVA_WORKSHOP = <?= json_encode($__aivaWorkshopMsg) ?>;
var AIVA_STEM     = <?= json_encode($__aivaStemMsg) ?>;
var R=[
  {p:['hello','hi','hey','howdy','good morning','good afternoon','hiya','greet'],
   r:"Hello! &#128075; I'm AIVA, your AI assistant for AI-Solutions. Ask me anything — services, pricing, events, gallery, blog, contact, or anything on our website!"},

  {p:['service','solution','offer','product','what do you','what can you','capabilities'],
   r:"We offer four core solutions:<br>&#129302; <b>AI Virtual Assistant</b> — from &pound;499/month<br>&#9889; <b>Rapid Prototyping</b> — from &pound;2,499/project<br>&#128202; <b>Digital Transformation</b> — custom quote<br>&#128295; <b>Custom AI Integration</b> — custom quote<br>Visit our <b>Solutions page</b> for full details!"},

  {p:['virtual assistant','ai assistant','chatbot','bot','aiva','conversational','nlp','natural language'],
   r:"Our <b>AI Virtual Assistant</b> handles employee queries automatically, integrates with Slack, Teams &amp; web, trains on your knowledge base, and delivers real-time analytics. From <b>&pound;499/month</b>. Book a free demo to see it live!"},

  {p:['prototyp','rapid','mvp','14 day','2 week','two week','idea to product','validate idea'],
   r:"Our <b>Rapid Prototyping</b> delivers a testable product in just <b>14 days</b> from &pound;2,499/project (fixed price).<br>Discovery Sprint &rarr; Build Sprint &rarr; User Testing &rarr; Handoff.<br>Perfect for validating ideas before full investment!"},

  {p:['digital transform','modernise','modernize','transform','digital employee','intranet'],
   r:"Our <b>Digital Transformation</b> service covers technology audit, roadmap, cloud migration &amp; ROI measurement — full end-to-end strategy. Custom pricing. Book a consultation to discuss your needs!"},

  {p:['custom ai','integrat','api','bespoke','erp','crm','hrms','legacy system','connect'],
   r:"Our <b>Custom AI Integration</b> connects AI to your existing ERP, CRM, HRMS and more. API-first, GDPR-compliant, legacy-compatible with ongoing SLA support. Pricing is scope-based — book a call to discuss!"},

  {p:['price','pricing','cost','how much','fee','budget','rate','plan','affordable','charge'],
   r:"&#128176; <b>Pricing at a glance:</b><br>&#129302; AI Virtual Assistant: <b>&pound;499/mo</b> (Starter) &middot; <b>&pound;999/mo</b> (Business) &middot; Custom (Enterprise)<br>&#9889; Rapid Prototyping: from <b>&pound;2,499</b>/project<br>&#128202; Digital Transformation: Custom<br>&#128295; Custom AI Integration: Custom<br>All plans include onboarding support!"},

  {p:['starter','499','small team','basic plan','starter plan'],
   r:"<b>Starter &mdash; &pound;499/month:</b><br>AI Virtual Assistant (500 queries/day), web channel, basic knowledge base &amp; email support. Perfect for small teams starting their AI journey!"},

  {p:['business plan','999','growing team','unlimited queries','business tier'],
   r:"<b>Business &mdash; &pound;999/month:</b><br>Unlimited queries, Slack + Teams integration, advanced analytics &amp; custom branding. Great for growing teams needing multi-channel AI!"},

  {p:['enterprise','sla','dedicated account','large company','enterprise plan'],
   r:"<b>Enterprise &mdash; Custom pricing:</b><br>Full API access, custom AI training, ERP/CRM/HRMS integration, dedicated account manager &amp; SLA guarantee. Contact our sales team to discuss!"},

  {p:['ongoing event','ongoing events','ongoing','running event','running events','happening today','happening now','today\'s event','today\'s events','live event','live events','current event','current events'],
   r:AIVA_ONGOING},

  {p:['upcoming event','upcoming events','future event','future events','next event','next events','event calendar','what\'s on','whats on','upcoming'],
   r:AIVA_UPCOMING},

  {p:['past event','past events','previous event','previous events','last event','last events','earlier event','earlier events'],
   r:AIVA_PAST},

  {p:['event','events','calendar','conference'],
   r:"&#128197; I can tell you about our events &mdash; ask me about <b>upcoming events</b>, what's <b>happening today</b>, or <b>past events</b>, and I'll give you just that list!"},

  {p:['ai summit','summit','keynote','stadium of light'],
   r:AIVA_SUMMIT},

  {p:['webinar','zoom','online event','ai in the workplace','workplace webinar'],
   r:AIVA_WEBINAR},

  {p:['meetup','tech meetup','networking','sunderland tech'],
   r:AIVA_MEETUP},

  {p:['sprint workshop','prototype sprint','workshop','hands-on'],
   r:AIVA_WORKSHOP},

  {p:['stem','school','student','university','outreach','year 10','year 13','schools programme'],
   r:AIVA_STEM},

  {p:['register','sign up','attend','ticket','enrol','join event','how to register'],
   r:"To register: visit our <b>Events page</b>, click any event card to see full details, then press <b>'Register Now'</b> to complete the registration form. Quick and free!"},

  {p:['gallery','photo','picture','image','visual','showcase','snap','see photo'],
   r:"&#128444;&#65039; Our <b>Photo Gallery</b> showcases AI Summit keynotes, team hackathons, client workshops, company retreats &amp; product launches. Visit the <b>Gallery page</b> to browse all photos!"},

  {p:['blog','article','post','insight','news','read','thought leadership','latest post'],
   r:"&#128221; Our <b>Blog</b> covers AI Strategy, Virtual Assistants, Prototyping, Industry Trends, Case Studies &amp; Engineering. Latest articles:<br>&bull; Why Every SME Needs an AI Strategy in 2026<br>&bull; 5 Ways AI Assistants Reduce Employee Burnout<br>&bull; The 2-Week Prototype Explained<br>Visit our <b>Blog page</b> to read them all!"},

  {p:['case stud','result','client result','example','success','achievement','impact','roi'],
   r:"&#128203; <b>Case Studies:</b><br>&#127973;&#65039; NorthCare NHS &mdash; 73% fewer HR queries, &pound;180K/year saved<br>&#128179; PayStream &mdash; 3&times; faster product validation<br>&#127981; SteelWorks UK &mdash; 5 systems unified<br>Visit our <b>Case Studies page</b> for the full stories!"},

  {p:['northcare','nhs','hospital','healthcare','health trust','hr queries'],
   r:"&#127973;&#65039; <b>NorthCare NHS Trust:</b> 3,400 staff, 800+ monthly HR queries. After AIVA deployment: <b>73% query reduction</b>, <b>&pound;180K annual saving</b>, 94% satisfaction (up from 61%), resolution time cut from 24 min &rarr; 2.8 min. Full case study on our website!"},

  {p:['paystream','pay stream','payment','fintech'],
   r:"&#128179; <b>PayStream</b> used our Rapid Prototyping and achieved <b>3&times; faster product validation</b> &mdash; compressing their 8-week cycle to just 14 days. Full case study on our Case Studies page!"},

  {p:['contact','email','phone','reach','get in touch','enquir','message us'],
   r:"&#128222; <b>Contact us:</b><br>&#128231; info@ai-solutions.co.uk<br>&#128222; +44 191 000 0000<br>&#128205; Sunderland, UK<br>Or use our <b>Contact page</b> to send a detailed enquiry!"},

  {p:['location','address','where are you','where is','sunderland','office','based'],
   r:"&#128205; We're based in <b>Sunderland, UK</b>. Our HQ is in Sunderland and we serve clients across the UK and internationally. Visit our Contact page for our full address!"},

  {p:['demo','book','schedule','consultation','appointment','show me','trial','free session'],
   r:"&#127919; Book a <b>FREE 30-minute demo</b> &mdash; no obligation! Visit our <b>Schedule Demo page</b> or email info@ai-solutions.co.uk to pick a slot. We tailor every demo to your industry and use case!"},

  {p:['about','who are you','company','mission','founded','team','startup','story','history'],
   r:"&#128640; <b>AI-Solutions</b> is an AI-powered startup in Sunderland, UK. Mission: <b>Innovate. Promote. Deliver.</b> We build intelligent virtual assistants &amp; affordable AI solutions for businesses of all sizes. 5+ years &middot; 150+ clients &middot; 40+ projects delivered!"},

  {p:['feedback','review','testimonial','opinion','satisfaction','survey','rate us'],
   r:"&#128172; We'd love your feedback! Visit our <b>Feedback page</b> to share your experience. Your input shapes our products &amp; services directly. We read every response!"},

  {p:['job','career','hiring','apply','work for','vacancy','cv','resume','role','opening'],
   r:"&#128188; We're hiring! Open roles: AI Engineering, Full-Stack Dev, Product Design &amp; Sales. Send your CV to <b>info@ai-solutions.co.uk</b> or use our Contact page. We'd love to hear from you!"},

  {p:['technolog','tech stack','built with','php','mongodb','how does it work','infrastructure','architecture'],
   r:"&#128295; Our stack: <b>PHP 8.x</b> (application), <b>MongoDB</b> (data storage), <b>Transformer NLP models</b> (intent classification), Redis (sessions). GDPR-compliant by design. Read our Engineering blog post for a full deep dive!"},

  {p:['linkedin','twitter','github','social','follow','social media'],
   r:"&#127760; Find us on <b>LinkedIn, Twitter &amp; GitHub</b> via the social icons in our page footer. We post AI insights, company updates &amp; job openings &mdash; follow us to stay in the loop!"},

  {p:['page','pages','menu','navigate','find','where is','section','link','site map','website'],
   r:"&#128506;&#65039; <b>Pages on our site:</b><br>&#127968; Home &middot; &#128161; Solutions &middot; &#128203; Case Studies<br>&#128444;&#65039; Gallery &middot; &#128197; Events &middot; &#128221; Blog<br>&#128172; Feedback &middot; &#128222; Contact &middot; &#127919; Schedule Demo<br>Use the top navigation bar to jump to any page!"},

  {p:['thank','thanks','great','perfect','helpful','awesome','brilliant','good job','cheers'],
   r:"You're welcome! &#128522; Anything else I can help with?"},

  {p:['bye','goodbye','later','see you','ciao','farewell','that\'s all','done'],
   r:"Goodbye! &#128075; Have a great day! Come back anytime &mdash; I'm always here. &#128522;"}
];

function askAiva(q){document.getElementById('at').value=q;sendA();}

function sendA(){
  var inp=document.getElementById('at'),t=inp.value.trim();
  if(!t)return;
  var m=document.getElementById('am');
  // Remove quick-reply chips on first message
  var chips=document.getElementById('aiva-chips');if(chips)chips.remove();
  // Append user message
  m.innerHTML+='<div class="um">'+t.replace(/</g,'&lt;')+'</div>';
  inp.value='';
  // Show typing indicator
  var td=document.createElement('div');
  td.className='bm typing-ind';td.id='aiva-t';
  td.innerHTML='<span></span><span></span><span></span>';
  m.appendChild(td);m.scrollTop=m.scrollHeight;
  // Score-based match: count keyword hits per rule, pick best
  var l=t.toLowerCase(),best=0,resp="Hmm, I'm not sure about that. &#128580; Try asking about our services, pricing, events, gallery, blog, or contact info &mdash; I know it all!";
  for(var i=0;i<R.length;i++){
    var s=0;for(var j=0;j<R[i].p.length;j++){var pat=R[i].p[j].replace(/[.*+?^${}()|[\]\\]/g,'\\$&');if(new RegExp('\\b'+pat+'\\b','i').test(l))s++;}
    if(s>best){best=s;resp=R[i].r;}
  }
  setTimeout(function(){
    var td2=document.getElementById('aiva-t');if(td2)td2.remove();
    m.innerHTML+='<div class="bm">'+resp+'</div>';
    m.scrollTop=m.scrollHeight;
  },750);
}
</script>
</body>
</html>
