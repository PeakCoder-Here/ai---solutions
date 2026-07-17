\# AI-Solutions Website



A full-stack business website built with PHP and MongoDB, featuring a public-facing marketing site and an admin dashboard for managing content and tracking inquiries.



\## Overview



AI-Solutions is a complete business web application covering everything from a public homepage and service pages to lead-generation forms (contact, demo requests, event registrations) backed by MongoDB, plus an admin dashboard with KPI reporting and charts for tracking activity across the site.



\## Tech Stack



\- \*\*Backend:\*\* PHP 8.x

\- \*\*Database:\*\* MongoDB

\- \*\*Dependency Management:\*\* Composer

\- \*\*Frontend:\*\* HTML, CSS, JavaScript (Chart.js for admin analytics)

\- \*\*Local Environment:\*\* XAMPP (Apache + PHP)



\## Requirements



Before setup, make sure you have:



\- PHP 8.x

\- \[Composer](https://getcomposer.org/)

\- MongoDB (running locally or via a hosted instance)

\- XAMPP or an equivalent Apache + PHP environment



\## Quick Start



1\. Clone or extract the project into your local server directory:

&nbsp;  ```

&nbsp;  C:\\xampp\\htdocs\\ai-solutions

&nbsp;  ```

2\. Install dependencies from the project root:

&nbsp;  ```

&nbsp;  composer require mongodb/mongodb

&nbsp;  ```

3\. Copy `.env.example` to `.env` and fill in your MongoDB connection string and other environment-specific values.

4\. Start \*\*Apache\*\* and \*\*MongoDB\*\* via XAMPP.

5\. Run the database setup script \*\*once\*\*, then delete it:

&nbsp;  ```

&nbsp;  http://localhost/ai-solutions/setup\_db.php

&nbsp;  ```

6\. Visit the site:

&nbsp;  ```

&nbsp;  http://localhost/ai-solutions/

&nbsp;  ```



\## Admin Login



\- URL: `http://localhost/ai-solutions/admin/login.php`

\- Credentials: see your local `.env` file, or contact a project admin.



> ⚠️ Never commit real credentials to this repository. Default/setup credentials should always be changed immediately after first login.



\## Features



| # | Feature | Page | Notes |

|---|---------|------|-------|

| 1 | Home Page | `index.php` | |

| 2 | Software Solutions | `solutions.php` | |

| 3 | Past Solutions | `case-studies.php` | |

| 4 | Photo Gallery | `gallery.php` | |

| 5 | Upcoming Events | `events.php` | |

| 6 | Articles / Blog | `blog.php` | |

| 7 | Customer Feedback | `feedback.php` | |

| 8 | Schedule a Demo | `schedule-demo.php` | MongoDB: `demo\_requests` |

| 9 | Contact Us | `contact.php` | MongoDB: `contact\_inquiries` |

| 10 | Join Our Events | `join-events.php` | MongoDB: `event\_registrations` |

| 11 | Admin Dashboard | `admin/dashboard.php` | KPIs, Chart.js, Mean/StdDev reporting |



\## Project Structure



```

ai-solutions/

├── admin/          # Admin dashboard \& management pages

├── includes/        # Shared PHP components (auth, db, config)

├── css/             # Stylesheets

├── js/              # Client-side scripts

├── images/          # Static image assets

├── docs/            # Project documentation

├── \*.php            # Public-facing pages (index, blog, contact, etc.)

├── composer.json     # PHP dependency manifest

├── setup\_db.php      # One-time database initialization script

├── .env              # Environment variables (not committed)

└── .gitignore

```



\## Environment Variables



This project expects a `.env` file (not committed to version control) containing at minimum:



```

MONGODB\_URI=your\_mongodb\_connection\_string

DB\_NAME=ai\_solutions

```



Adjust based on your actual configuration in `includes/config.php`.



\## Contributing



This is currently a single-maintainer project. If that changes, contribution guidelines will be added here.



\## License



This project is licensed under the MIT License — see the \[LICENSE](LICENSE) file for details.

