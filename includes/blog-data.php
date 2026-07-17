<?php
/**
 * blog-data.php — Seed data for the `blog_posts` MongoDB collection
 *
 * blog.php and admin/manage-blog.php both auto-seed this data into
 * $db->blog_posts the first time the collection is empty (same pattern
 * gallery.php already uses for the `gallery` collection). After that,
 * the blog is fully DB-driven and editable from admin/manage-blog.php —
 * this file is only ever read again if the collection gets emptied.
 *
 * Each entry: slug, title, excerpt, content (HTML), tag, author, img,
 * published_at (Y-m-d).
 */

$blogSeed = [
    [
        'slug'         => 'ai-virtual-assistants-2026',
        'title'        => 'Why AI Virtual Assistants Are Becoming Core Infrastructure in 2026',
        'excerpt'      => 'From HR helpdesks to customer support, conversational AI has moved from novelty to necessity. Here is what separates a good deployment from a great one.',
        'content'      => '<p>Three years ago, an AI virtual assistant was a nice-to-have — a chatbot bolted onto a support page to deflect the easiest tickets. In 2026, it is core infrastructure, sitting alongside email and identity management as something every mid-sized organisation is expected to have.</p>' .
                           '<p>What changed is not just model quality. It is deployment discipline. The organisations getting real value are the ones that trained their assistant on a genuinely current knowledge base, gave it clear escalation paths to a human, and measured deflection rate against satisfaction — not instead of it.</p>' .
                           '<p>At AI-Solutions, our own NorthCare NHS Trust deployment deflected 73% of repetitive HR queries in the first quarter, but the number that mattered more internally was a 4.6-star staff satisfaction rating. Deflection without satisfaction is just a worse help desk.</p>' .
                           '<p>If you are scoping a virtual assistant for 2026, start with the documents, not the model: an assistant is only as good as what it has been given to read.</p>',
        'tag'          => 'AI Strategy',
        'author'       => 'AI-Solutions Team',
        'img'          => 'https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=600&h=400&fit=crop&auto=format',
        'published_at' => '2026-07-02',
    ],
    [
        'slug'         => 'northcare-nhs-case-study',
        'title'        => 'How NorthCare NHS Trust Cut Helpdesk Load by 73% with an AI Assistant',
        'excerpt'      => 'A behind-the-scenes look at deploying a Microsoft Teams-integrated virtual assistant trained on 500+ HR policy documents.',
        'content'      => '<p>NorthCare NHS Trust came to us with a familiar problem at a large scale: 12,000+ repetitive HR queries a month, all funnelled through a small internal helpdesk team that was drowning in "how many annual leave days do I have left" and "where do I find the parental leave policy" tickets.</p>' .
                           '<p>We deployed an AI Virtual Assistant integrated directly into the trust\'s Microsoft Teams environment, trained on more than 500 HR policy documents so it could answer with the trust\'s actual policy language, not a generic approximation.</p>' .
                           '<p>The results after the first full quarter: 73% query deflection, an estimated £180K in annual savings, and a 4.6-star satisfaction rating from staff who no longer had to wait days for an answer to a two-line question.</p>' .
                           '<p>The lesson that generalises beyond healthcare: deflection rate is a vanity metric on its own. It only means something paired with a satisfaction score that proves people are getting good answers, not just fewer humans to talk to.</p>',
        'tag'          => 'Case Study',
        'author'       => 'Priya Anand',
        'img'          => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=600&h=400&fit=crop&auto=format',
        'published_at' => '2026-06-24',
    ],
    [
        'slug'         => 'rapid-prototyping-14-days',
        'title'        => 'From Idea to Prototype in 14 Days: Our Rapid Prototyping Playbook',
        'excerpt'      => 'The exact process we use to take a product concept from whiteboard to user-tested prototype in two weeks flat.',
        'content'      => '<p>PayStream Ltd came to us with an 8-week product validation cycle that was costing them market windows. Our Rapid Prototyping engagement compressed that into 14 days, and it is a process we now run for most clients validating a new product idea.</p>' .
                           '<p>Week one is entirely about narrowing scope: we run structured stakeholder interviews to identify the one workflow that, if it worked, would prove the whole concept. Everything else is deliberately left out of the prototype.</p>' .
                           '<p>Week two is build and test in parallel — a working interactive prototype goes in front of real users from day nine onward, and every session feeds directly back into the next day\'s build. By day fourteen we are not guessing whether the concept works, we have user data.</p>' .
                           '<p>For PayStream, this meant three interactive prototypes tested with 200+ users in fourteen days, a 3x faster validation cycle, and 92% user approval on the concept that shipped.</p>',
        'tag'          => 'Rapid Prototyping',
        'author'       => 'James Okafor',
        'img'          => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=400&fit=crop&auto=format',
        'published_at' => '2026-06-15',
    ],
    [
        'slug'         => 'digital-employee-experience',
        'title'        => 'The Future of Digital Employee Experience: 5 Trends to Watch',
        'excerpt'      => 'Employee-facing AI tools are catching up fast to their customer-facing counterparts. Here is where the gap is closing quickest.',
        'content'      => '<p>Customer-facing AI has had a multi-year head start over anything built for employees. That gap is closing quickly, and five trends explain most of why.</p>' .
                           '<p>First, internal assistants are finally being trained on live, versioned policy documents instead of static PDFs, which is what makes deflection rates like NorthCare\'s possible. Second, HR and IT are converging their AI tooling instead of running separate chatbots. Third, employees increasingly expect the same conversational quality at work that they get from consumer apps, and will route around clunky internal tools if it is not there.</p>' .
                           '<p>Fourth, measurement is maturing — organisations are moving past "tickets deflected" toward genuine satisfaction and time-saved metrics. Fifth, and most underrated: the best internal deployments are being scoped by the same rigour as customer-facing products, with real discovery and real user testing, not just a policy dump into a model.</p>' .
                           '<p>The organisations that treat employee experience with product discipline are the ones seeing the biggest gains.</p>',
        'tag'          => 'Digital Transformation',
        'author'       => 'AI-Solutions Team',
        'img'          => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=600&h=400&fit=crop&auto=format',
        'published_at' => '2026-06-03',
    ],
    [
        'slug'         => 'legacy-systems-integration',
        'title'        => 'Unifying Legacy Systems Without a Rip-and-Replace',
        'excerpt'      => 'Lessons from connecting five disconnected manufacturing systems into a single intelligent dashboard, without a single afternoon of downtime.',
        'content'      => '<p>SteelWorks UK had a problem common to manufacturers with a long operating history: five separate systems — ERP, HRMS, and three generations of quality databases — with no cross-platform visibility for managers.</p>' .
                           '<p>The instinct in situations like this is often to propose a full replatform. We didn\'t. A rip-and-replace on live manufacturing systems is a multi-year, high-risk project that most operations teams (rightly) resist.</p>' .
                           '<p>Instead, we built a Custom AI Integration layer that read from all five systems and presented a single, intelligent dashboard to managers, without touching the underlying systems of record. Zero downtime during the migration, because there was no migration — only a new layer on top.</p>' .
                           '<p>Result: five systems unified, a 40% reduction in time spent reconciling data across departments, and positive ROI within six months. The broader lesson: integration is very often a better answer than replacement.</p>',
        'tag'          => 'Engineering',
        'author'       => 'Marcus Lindqvist',
        'img'          => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=400&fit=crop&auto=format',
        'published_at' => '2026-05-20',
    ],
    [
        'slug'         => 'measuring-ai-roi',
        'title'        => 'Measuring AI ROI: The Metrics That Actually Matter to the Board',
        'excerpt'      => 'Query deflection rate and satisfaction scores look good in a deck, but they rarely win budget on their own. Here is what does.',
        'content'      => '<p>Query deflection rate and satisfaction scores are the metrics every AI vendor leads with, including us. They are useful operational indicators. They rarely win the next budget cycle on their own.</p>' .
                           '<p>What actually moves a board conversation is a translated cost figure: NorthCare\'s 73% deflection became a £180K annual saving. PayStream\'s 3x faster validation became "we can ship three more product bets a year with the same team." SteelWorks\' unified dashboard became a 40% reduction in a specific, previously budgeted line item — reconciliation labour.</p>' .
                           '<p>The pattern: pick the one number your board already tracks — headcount cost, time-to-market, reconciliation hours, ticket backlog — and show the AI deployment\'s effect on that specific number. Deflection rate is an input. The board wants the output.</p>' .
                           '<p>If you can\'t yet translate your AI metrics into a number your finance team already reports on, that is the gap to close before the next renewal conversation.</p>',
        'tag'          => 'Industry Insight',
        'author'       => 'AI-Solutions Team',
        'img'          => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop&auto=format',
        'published_at' => '2026-05-08',
    ],
];
