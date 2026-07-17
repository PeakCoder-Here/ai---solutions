<?php
/**
 * index.php — Home page (template test)
 * Place in: C:\xampp\htdocs\ai-solutions\index.php
 */
$pageTitle   = 'Home';
$currentPage = 'home';
$base        = '';

require_once(__DIR__ . '/includes/header.php');
?>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <div class="hero__split">
                <div class="hero__inner">
                    <div class="hero__badge"><i class="fa fa-robot"></i> AI-Powered Solutions</div>
                    <h1>Innovate. Promote. Deliver.</h1>
                    <p>The Future of Digital Employee Experience — built with intelligent virtual assistants and affordable AI prototyping.</p>
                    <div class="hero__actions">
                        <a href="schedule-demo.php" class="btn btn-primary btn-lg">Schedule a Demo</a>
                        <a href="solutions.php"     class="btn btn-outline btn-lg">Our Solutions</a>
                    </div>
                </div>
                <div class="hero__img">
                    <img src="https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=650&h=500&fit=crop&auto=format"
                         alt="AI neural network technology" loading="eager">
                </div>
            </div>
        </div>
    </section>

    <!-- KPI strip -->
    <section class="section section--grey" style="padding: 3rem 0;">
        <div class="container grid-4">
            <div class="card kpi-card">
                <div class="kpi-card__value" data-count="150">0</div>
                <div class="kpi-card__label">Clients Served</div>
            </div>
            <div class="card kpi-card">
                <div class="kpi-card__value" data-count="40">0</div>
                <div class="kpi-card__label">Projects Delivered</div>
            </div>
            <div class="card kpi-card">
                <div class="kpi-card__value" data-count="98">0</div>
                <div class="kpi-card__label">% Satisfaction</div>
            </div>
            <div class="card kpi-card">
                <div class="kpi-card__value" data-count="5">0</div>
                <div class="kpi-card__label">Years Experience</div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="section">
        <div class="container">
            <div class="section__header">
                <h2>What We Offer</h2>
                <p>AI-powered tools designed to transform how your team works, communicates, and delivers results.</p>
            </div>
            <div class="grid-3">
                <div class="card service-card">
                    <div class="card__icon"><i class="fa fa-comments"></i></div>
                    <h3>AI Virtual Assistant</h3>
                    <p>Intelligent chatbot and assistant solutions tailored to your business processes and employee needs.</p>
                    <a href="solutions.php" class="btn btn-primary btn-sm mt-3">Learn More</a>
                </div>
                <div class="card service-card">
                    <div class="card__icon"><i class="fa fa-flask"></i></div>
                    <h3>Rapid Prototyping</h3>
                    <p>Affordable, fast-turnaround prototype development so you can validate ideas before full investment.</p>
                    <a href="solutions.php" class="btn btn-primary btn-sm mt-3">Learn More</a>
                </div>
                <div class="card service-card">
                    <div class="card__icon"><i class="fa fa-chart-bar"></i></div>
                    <h3>Digital Transformation</h3>
                    <p>End-to-end consulting and delivery to modernise your digital employee experience infrastructure.</p>
                    <a href="solutions.php" class="btn btn-primary btn-sm mt-3">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="section section--navy">
        <div class="container text-center">
            <h2>Ready to Transform Your Business?</h2>
            <p style="margin-bottom:2rem; opacity:0.85;">Book a free demo and see exactly how AI-Solutions can deliver value for your organisation.</p>
            <a href="schedule-demo.php" class="btn btn-outline btn-lg">Schedule a Free Demo</a>
        </div>
    </section>

<?php require_once(__DIR__ . '/includes/footer.php'); ?>