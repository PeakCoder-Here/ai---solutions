<?php
$pageTitle = 'Software Solutions'; $currentPage = 'solutions'; $base = '';
require_once(__DIR__ . '/includes/header.php');
?>

<section class="page-banner">
    <div class="container">
        <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Solutions</p>
        <h1>Software Solutions</h1>
        <p>AI-powered tools engineered for the modern digital workplace.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header">
            <h2>Our Products & Services</h2>
            <p>From intelligent assistants to rapid prototyping — solutions that scale with your ambition.</p>
        </div>
        <div class="grid-2">
            <div class="card service-card">
                <div class="service-card__img">
                    <img src="https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=600&h=200&fit=crop&auto=format" alt="AI Virtual Assistant" loading="lazy">
                </div>
                <div class="card__icon"><i class="fa fa-comments"></i></div>
                <h3>AI Virtual Assistant</h3>
                <p>Our flagship product — a context-aware conversational assistant that integrates with your existing tools. Handles employee queries, automates tasks, and learns from every interaction. <strong>Pricing scales with your team size and features</strong> — configure it below.</p>
                <ul style="margin:1rem 0;padding-left:1.25rem;list-style:disc;color:var(--grey-text);">
                    <li>Natural language understanding (NLU)</li>
                    <li>Multi-channel: web, Slack, Teams</li>
                    <li>Custom knowledge base training</li>
                    <li>Real-time analytics dashboard</li>
                </ul>
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-top:.75rem;">
                    <span class="badge badge-blue">Flagship Product</span>
                    <a href="#price-configurator" class="btn btn-primary btn-sm">Configure my price &darr;</a>
                </div>
            </div>
            <div class="card service-card">
                <div class="service-card__img">
                    <img src="https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=600&h=200&fit=crop&auto=format" alt="Rapid Prototyping" loading="lazy">
                </div>
                <div class="card__icon"><i class="fa fa-flask"></i></div>
                <h3>Rapid Prototyping</h3>
                <p>Validate product ideas in days, not months. Interactive prototypes with AI-enhanced features at a fraction of full development cost.</p>
                <ul style="margin:1rem 0;padding-left:1.25rem;list-style:disc;color:var(--grey-text);">
                    <li>2-week prototype turnaround</li>
                    <li>Interactive clickable demos</li>
                    <li>User feedback integration</li>
                    <li>Seamless handoff to full dev</li>
                </ul>
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-top:.75rem;">
                    <span class="badge badge-green">Most Popular</span>
                    <a href="contact.php" class="btn btn-primary btn-sm">Get a quote &rarr;</a>
                </div>
            </div>
            <div class="card service-card">
                <div class="service-card__img">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&h=200&fit=crop&auto=format" alt="Digital Transformation" loading="lazy">
                </div>
                <div class="card__icon"><i class="fa fa-chart-line"></i></div>
                <h3>Digital Transformation</h3>
                <p>End-to-end strategy for organisations modernising their digital employee experience. Audit, plan, and deliver measurable improvements.</p>
                <ul style="margin:1rem 0;padding-left:1.25rem;list-style:disc;color:var(--grey-text);">
                    <li>Technology audit & gap analysis</li>
                    <li>Roadmap & change management</li>
                    <li>Cloud migration support</li>
                    <li>ROI measurement framework</li>
                </ul>
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-top:.75rem;">
                    <span class="badge badge-navy">Enterprise</span>
                    <a href="contact.php" class="btn btn-primary btn-sm">Talk to sales &rarr;</a>
                </div>
            </div>
            <div class="card service-card">
                <div class="service-card__img">
                    <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=600&h=200&fit=crop&auto=format" alt="Custom AI Integration" loading="lazy">
                </div>
                <div class="card__icon"><i class="fa fa-cogs"></i></div>
                <h3>Custom AI Integration</h3>
                <p>Bespoke AI integrations connecting to your ERP, CRM, HRMS — adding intelligence without disrupting what works.</p>
                <ul style="margin:1rem 0;padding-left:1.25rem;list-style:disc;color:var(--grey-text);">
                    <li>API-first architecture</li>
                    <li>Legacy system compatibility</li>
                    <li>GDPR compliance built-in</li>
                    <li>Ongoing support & SLA</li>
                </ul>
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-top:.75rem;">
                    <span class="badge badge-red">Custom</span>
                    <a href="contact.php" class="btn btn-primary btn-sm">Talk to sales &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ── Custom Price Configurator ────────────────────────────── */
.pc2{padding:0;overflow:hidden;}
.pc2__header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;padding:1.5rem 2rem;border-bottom:1px solid var(--grey);}
.pc2__header h3{margin:0;}
.pc2__header p{margin:.2rem 0 0;color:var(--grey-text);font-size:.9rem;}
.pc2__billing{display:flex;align-items:center;gap:.6rem;}
.pc2__bill-lbl{font-size:.85rem;font-weight:600;color:var(--grey-text);transition:color .2s;}
.pc2__bill-lbl.active{color:var(--navy);}
.pc2__bill-switch{position:relative;width:44px;height:24px;cursor:pointer;display:inline-block;}
.pc2__bill-switch input{opacity:0;width:0;height:0;}
.pc2__bill-switch span{position:absolute;inset:0;background:#CBD5E1;border-radius:12px;transition:background .2s;}
.pc2__bill-switch span::after{content:'';position:absolute;width:18px;height:18px;background:#fff;border-radius:50%;top:3px;left:3px;transition:transform .2s;}
.pc2__bill-switch input:checked+span{background:var(--blue);}
.pc2__bill-switch input:checked+span::after{transform:translateX(20px);}
.pc2__body{display:grid;grid-template-columns:1fr 320px;}
.pc2__controls{padding:1.75rem 2rem;display:flex;flex-direction:column;gap:1.5rem;border-right:1px solid var(--grey);}
.pc2__summary{padding:1.75rem 1.5rem;background:#F8FAFC;display:flex;flex-direction:column;}
.pc2__ctrl-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:.6rem;}
.pc2__ctrl-hd label,.pc2__group-lbl{font-weight:700;font-size:.8rem;color:var(--navy);text-transform:uppercase;letter-spacing:.05em;}
.pc2__group-lbl{display:block;margin-bottom:.6rem;}
.pc2__ctrl-val{font-size:.92rem;font-weight:700;color:var(--blue);}
.pc2__slider{width:100%;-webkit-appearance:none;height:5px;border-radius:3px;background:#E2E8F0;outline:none;margin-bottom:.35rem;cursor:pointer;}
.pc2__slider::-webkit-slider-thumb{-webkit-appearance:none;width:20px;height:20px;background:var(--blue);border-radius:50%;cursor:pointer;border:3px solid #fff;box-shadow:0 0 0 2px var(--blue);}
.pc2__slider::-moz-range-thumb{width:20px;height:20px;background:var(--blue);border-radius:50%;cursor:pointer;border:3px solid #fff;}
.pc2__slider-labels{display:flex;justify-content:space-between;font-size:.73rem;color:var(--grey-text);}
.pc2__toggles{display:flex;flex-wrap:wrap;gap:.5rem;}
.pc2__tog{display:inline-flex;align-items:center;gap:.4rem;padding:.38rem .8rem;border:1.5px solid #E2E8F0;border-radius:20px;cursor:pointer;font-size:.83rem;font-weight:500;color:var(--grey-text);transition:all .15s;user-select:none;}
.pc2__tog input{display:none;}
.pc2__tog--on{background:var(--blue-light);border-color:var(--blue);color:var(--blue);font-weight:700;}
.pc2__tog-price{font-size:.72rem;opacity:.7;margin-left:.15rem;}
.pc2__features{display:grid;grid-template-columns:1fr 1fr;gap:.45rem;}
.pc2__feat{cursor:pointer;}
.pc2__feat input{display:none;}
.pc2__feat-box{display:flex;align-items:center;gap:.4rem;padding:.42rem .7rem;border:1.5px solid #E2E8F0;border-radius:8px;font-size:.82rem;color:var(--body-text);transition:all .15s;}
.pc2__feat-box b{color:var(--blue);margin-left:auto;font-size:.76rem;white-space:nowrap;}
.pc2__feat-box i{color:var(--grey-text);width:14px;text-align:center;transition:color .15s;}
.pc2__feat input:checked+.pc2__feat-box{background:var(--blue-light);border-color:var(--blue);font-weight:600;}
.pc2__feat input:checked+.pc2__feat-box i{color:var(--blue);}
/* Summary panel */
.pc2__tier-badge{display:inline-block;background:var(--blue);color:#fff;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;padding:.22rem .8rem;border-radius:20px;margin-bottom:1rem;}
.pc2__tier-badge--ent{background:var(--violet,#7C3AED);}
.pc2__bk{display:flex;flex-direction:column;gap:.3rem;flex:1;}
.pc2__bk-line{display:flex;justify-content:space-between;gap:.5rem;font-size:.8rem;color:var(--grey-text);padding:.15rem 0;}
.pc2__bk-line+.pc2__bk-line{border-top:1px dashed #E2E8F0;}
.pc2__bk-line span:last-child{font-weight:700;white-space:nowrap;color:var(--navy);}
.pc2__bk-disc span:last-child{color:#10B981!important;}
.pc2__total{border-top:2px solid #E2E8F0;padding-top:.9rem;margin-top:.9rem;}
.pc2__total-price{font-family:var(--font-heading);font-size:2.4rem;font-weight:900;color:var(--navy);line-height:1;}
.pc2__total-price span{font-size:1rem;font-weight:400;color:var(--grey-text);}
.pc2__total-annual{font-size:.78rem;color:#10B981;font-weight:600;margin-top:.3rem;}
.pc2__ent-msg{flex:1;display:flex;flex-direction:column;justify-content:center;text-align:center;padding:.75rem 0;}
.pc2__ent-msg p{margin:.4rem 0;font-size:.88rem;line-height:1.6;color:var(--body-text);}
@media(max-width:900px){
    .pc2__body{grid-template-columns:1fr;}
    .pc2__controls{border-right:none;border-bottom:1px solid var(--grey);}
    .pc2__summary{background:#EEF2FF;}
    .pc2__features{grid-template-columns:1fr;}
}
@media(max-width:480px){
    .pc2__header,.pc2__controls,.pc2__summary{padding:1.1rem;}
    .pc2__toggles{flex-direction:column;}
}
</style>

<section class="section section--grey" id="price-configurator">
    <div class="container">
        <div class="section__header">
            <h2>Build Your Custom Price</h2>
            <p>Drag the sliders, tick the features you need — your price updates instantly.</p>
        </div>

        <div class="card pc2" style="margin-bottom:2.5rem;">
            <!-- Header + billing toggle -->
            <div class="pc2__header">
                <div>
                    <h3>Price Configurator</h3>
                    <p>Only pay for what you actually need.</p>
                </div>
                <div class="pc2__billing">
                    <span class="pc2__bill-lbl active" id="bl-monthly">Monthly</span>
                    <label class="pc2__bill-switch">
                        <input type="checkbox" id="pc_annual" onchange="recalc()">
                        <span></span>
                    </label>
                    <span class="pc2__bill-lbl" id="bl-annual">Annual &nbsp;<span class="badge badge-green" style="font-size:.68rem;">Save 15%</span></span>
                </div>
            </div>

            <div class="pc2__body">
                <!-- Controls -->
                <div class="pc2__controls">

                    <!-- Team size -->
                    <div>
                        <div class="pc2__ctrl-hd">
                            <label>Team Size</label>
                            <span id="lbl-users" class="pc2__ctrl-val">25 people</span>
                        </div>
                        <input type="range" id="pc_users" class="pc2__slider" min="1" max="200" value="25" step="1" oninput="recalc()">
                        <div class="pc2__slider-labels"><span>1</span><span>100</span><span>200+</span></div>
                    </div>

                    <!-- Queries per day -->
                    <div>
                        <div class="pc2__ctrl-hd">
                            <label>Queries / Day</label>
                            <span id="lbl-queries" class="pc2__ctrl-val">500/day</span>
                        </div>
                        <input type="range" id="pc_queries" class="pc2__slider" min="0" max="5" value="0" step="1" oninput="recalc()">
                        <div class="pc2__slider-labels"><span>500</span><span>5,000</span><span>Unlimited</span></div>
                    </div>

                    <!-- Channels -->
                    <div>
                        <span class="pc2__group-lbl">Channels</span>
                        <div class="pc2__toggles">
                            <span class="pc2__tog pc2__tog--on"><i class="fa fa-globe"></i> Web <span class="pc2__tog-price">included</span></span>
                            <label class="pc2__tog" id="tog-slack">
                                <input type="checkbox" id="pc_slack" onchange="recalc()">
                                <i class="fa fa-hashtag"></i> Slack <span class="pc2__tog-price">+£79</span>
                            </label>
                            <label class="pc2__tog" id="tog-teams">
                                <input type="checkbox" id="pc_teams" onchange="recalc()">
                                <i class="fa fa-th-large"></i> Teams <span class="pc2__tog-price">+£79</span>
                            </label>
                        </div>
                    </div>

                    <!-- Features -->
                    <div>
                        <span class="pc2__group-lbl">Features &amp; Add-ons</span>
                        <div class="pc2__features">
                            <label class="pc2__feat"><input type="checkbox" id="pc_brand"   onchange="recalc()"><span class="pc2__feat-box"><i class="fa fa-palette"></i> Custom branding <b>+£49</b></span></label>
                            <label class="pc2__feat"><input type="checkbox" id="pc_analy"   onchange="recalc()"><span class="pc2__feat-box"><i class="fa fa-chart-bar"></i> Analytics <b>+£79</b></span></label>
                            <label class="pc2__feat"><input type="checkbox" id="pc_api"     onchange="recalc()"><span class="pc2__feat-box"><i class="fa fa-code"></i> API access <b>+£149</b></span></label>
                            <label class="pc2__feat"><input type="checkbox" id="pc_train"   onchange="recalc()"><span class="pc2__feat-box"><i class="fa fa-brain"></i> AI training <b>+£199</b></span></label>
                            <label class="pc2__feat"><input type="checkbox" id="pc_erp"     onchange="recalc()"><span class="pc2__feat-box"><i class="fa fa-plug"></i> ERP/CRM <b>+£249</b></span></label>
                            <label class="pc2__feat"><input type="checkbox" id="pc_support" onchange="recalc()"><span class="pc2__feat-box"><i class="fa fa-headset"></i> Dedicated support <b>+£199</b></span></label>
                        </div>
                    </div>

                </div>

                <!-- Live price summary -->
                <div class="pc2__summary" id="pc2-summary"></div>
            </div>
        </div>

        <!-- Rapid Prototyping banner -->
        <div style="padding:1.5rem 2rem;background:var(--white);border-radius:var(--radius-lg);border:1px solid var(--grey);box-shadow:var(--shadow-sm);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <h3 style="margin-bottom:.25rem;">Rapid Prototyping</h3>
                <p style="margin:0;color:var(--grey-text);">From <strong style="color:var(--navy);font-size:1.15rem;">£2,499</strong> per project &middot; 14-day turnaround &middot; Fixed price</p>
            </div>
            <a href="schedule-demo.php" class="btn btn-primary btn-sm">Book Discovery Call</a>
        </div>
    </div>
</section>

<section class="section section--navy">
    <div class="container text-center">
        <h2>Not sure which solution fits?</h2>
        <p style="margin-bottom:2rem;opacity:0.85;">Book a free 30-minute consultation and we'll recommend the right approach.</p>
        <a href="schedule-demo.php" class="btn btn-outline btn-lg">Schedule a Demo</a>
    </div>
</section>

<script>
var Q_LABEL = ['500/day','1,000/day','2,000/day','5,000/day','10,000/day','Unlimited'];
var Q_PRICE = [0, 50, 100, 150, 200, 300];

function recalc() {
    var users   = parseInt(document.getElementById('pc_users').value);
    var qIdx    = parseInt(document.getElementById('pc_queries').value);
    var annual  = document.getElementById('pc_annual').checked;
    var slack   = document.getElementById('pc_slack').checked;
    var teams   = document.getElementById('pc_teams').checked;
    var brand   = document.getElementById('pc_brand').checked;
    var analy   = document.getElementById('pc_analy').checked;
    var api     = document.getElementById('pc_api').checked;
    var train   = document.getElementById('pc_train').checked;
    var erp     = document.getElementById('pc_erp').checked;
    var support = document.getElementById('pc_support').checked;

    // Update live labels
    document.getElementById('lbl-users').textContent   = users >= 200 ? '200+ (Enterprise)' : users + ' people';
    document.getElementById('lbl-queries').textContent = Q_LABEL[qIdx];

    // Toggle active states on channel pills
    ['pc_slack','pc_teams'].forEach(function(id) {
        var lbl = document.getElementById(id).closest('.pc2__tog');
        if (lbl) lbl.classList.toggle('pc2__tog--on', document.getElementById(id).checked);
    });

    // Billing label highlight
    document.getElementById('bl-monthly').classList.toggle('active', !annual);
    document.getElementById('bl-annual').classList.toggle('active', annual);

    // Enterprise check
    if (users >= 200 || (api && erp) || (api && train && erp)) {
        renderEnterprise(); return;
    }

    // Cost components
    var base      = 299;
    var extraU    = Math.max(0, users - 25) * 6;
    var qCost     = Q_PRICE[qIdx];
    var slackC    = slack   ? 79  : 0;
    var teamsC    = teams   ? 79  : 0;
    var brandC    = brand   ? 49  : 0;
    var analyC    = analy   ? 79  : 0;
    var apiC      = api     ? 149 : 0;
    var trainC    = train   ? 199 : 0;
    var erpC      = erp     ? 249 : 0;
    var suppC     = support ? 199 : 0;
    var subtotal  = base + extraU + qCost + slackC + teamsC + brandC + analyC + apiC + trainC + erpC + suppC;
    var discount  = annual ? Math.round(subtotal * 0.15) : 0;
    var total     = subtotal - discount;

    // Tier label
    var tier = 'Starter';
    if (users > 25 || qIdx > 0 || slack || teams || brand || analy) tier = 'Business';
    if (api || train || erp || support || users > 100) tier = 'Enterprise';

    // Build breakdown
    var lines = [{l:'Base (25 users · 500/day · web)', c: base}];
    if (extraU)  lines.push({l:'Extra users (' + (users-25) + ' × £6/mo)',     c: extraU});
    if (qCost)   lines.push({l:'Queries: ' + Q_LABEL[qIdx],                    c: qCost});
    if (slackC)  lines.push({l:'Slack integration',                             c: slackC});
    if (teamsC)  lines.push({l:'Microsoft Teams',                               c: teamsC});
    if (brandC)  lines.push({l:'Custom branding',                               c: brandC});
    if (analyC)  lines.push({l:'Advanced analytics',                            c: analyC});
    if (apiC)    lines.push({l:'API access',                                    c: apiC});
    if (trainC)  lines.push({l:'Custom AI training',                            c: trainC});
    if (erpC)    lines.push({l:'ERP / CRM integration',                         c: erpC});
    if (suppC)   lines.push({l:'Dedicated support',                             c: suppC});

    var bk = lines.map(function(x) {
        return '<div class="pc2__bk-line"><span>'+x.l+'</span><span>£'+x.c+'</span></div>';
    }).join('');
    if (discount) bk += '<div class="pc2__bk-line pc2__bk-disc"><span>Annual 15% discount</span><span>-£'+discount+'</span></div>';

    var annLine = annual ? '<div class="pc2__total-annual">= £'+(total*12)+' billed annually</div>' : '';
    var ctaHref = tier === 'Enterprise' ? 'contact.php' : 'checkout.php?plan='+encodeURIComponent(tier)
        +'&users='+users
        +(qIdx    ? '&q='+qIdx   : '')
        +(slack   ? '&slack=1'   : '')
        +(teams   ? '&teams=1'   : '')
        +(brand   ? '&brand=1'   : '')
        +(analy   ? '&analy=1'   : '')
        +(api     ? '&api=1'     : '')
        +(train   ? '&train=1'   : '')
        +(erp     ? '&erp=1'     : '')
        +(support ? '&support=1' : '')
        +(annual  ? '&annual=1'  : '');
    var ctaTxt  = tier === 'Enterprise' ? 'Talk to Sales &rarr;' : 'Get This Plan &rarr;';

    document.getElementById('pc2-summary').innerHTML =
        '<div class="pc2__tier-badge'+(tier==='Enterprise'?' pc2__tier-badge--ent':'')+'">'+tier+'</div>'+
        '<div class="pc2__bk">'+bk+'</div>'+
        '<div class="pc2__total">'+
            '<div class="pc2__total-price">£'+total+'<span>/mo</span></div>'+
            annLine+
        '</div>'+
        '<a href="'+ctaHref+'" class="btn btn-primary" style="width:100%;text-align:center;margin-top:1rem;">'+ctaTxt+'</a>'+
        '<p style="font-size:.75rem;color:var(--grey-text);text-align:center;margin:.65rem 0 0;">Estimate only — confirmed after consultation.</p>';
}

function renderEnterprise() {
    document.getElementById('bl-monthly').classList.remove('active');
    document.getElementById('bl-annual').classList.remove('active');
    document.getElementById('pc2-summary').innerHTML =
        '<div class="pc2__tier-badge pc2__tier-badge--ent">Enterprise</div>'+
        '<div class="pc2__ent-msg">'+
            '<i class="fa fa-building" style="font-size:2.2rem;color:var(--violet,#7C3AED);display:block;margin-bottom:.75rem;"></i>'+
            '<p>Your requirements exceed our standard tiers — we\'ll price this individually based on your volume, integrations, and SLA needs.</p>'+
            '<p style="margin-top:.5rem;"><strong>Typical Enterprise starts from £1,999/month.</strong></p>'+
        '</div>'+
        '<a href="contact.php" class="btn btn-navy" style="width:100%;text-align:center;margin-top:1rem;">Talk to Our Sales Team &rarr;</a>'+
        '<p style="font-size:.75rem;color:var(--grey-text);text-align:center;margin:.65rem 0 0;">Free consultation, no obligation.</p>';
}

recalc();
</script>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
