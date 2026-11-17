# 👑 Uncrown — WordPress Team Portfolio

## 🌐 Live Website

Visit our deployed WordPress Team Portfolio:

https://earthy-tan-giraffe-34.hostingersite.com/

## 📌 Project Overview

The Uncrown WordPress Team Portfolio is a team-developed website created as our CLOUD Computing project for the Bachelor of Science in Information Technology (BSIT) program.

The portfolio introduces the members of team Uncrown, their roles and skill levels, our working process, and the systems we have built. It runs on a custom WordPress theme, **Uncrown Portfolio**, designed around our black-and-gold logo. The homepage opens with a scroll-driven cinematic intro, and each project has its own landing page with live demo videos of the system on web, tablet and phone screens.

## 🎯 Project Objectives

- Develop a professional, responsive and accessible team portfolio website.
- Present each member's role, profile and measured skill levels, with a team skills chart.
- Showcase the team's systems with demo videos, key features and case-study pages.
- Provide a private contact form that can reach the whole team or one member.
- Link the team's social media and GitHub profiles.
- Apply WordPress theme development: custom post types, Customizer settings and template parts.
- Use Git and GitHub for version control and progress tracking.
- Deploy the website to a live web hosting platform.

## 🗂️ Featured Projects

| Project | Platforms | Description |
|---|---|---|
| **5C Electrical Business Management System** | Web · POS tablet · Delivery phone app | One system for 5C Electrical: a web dashboard for owners, a POS tablet app for the counter and a delivery app for drivers, all sharing the same sales and stock data. |
| **eVote Campus Election System** | Admin web · Voter mobile app | A campus election system with an admin console for the election committee and a mobile app for voters, from sign-in to a locked, receipted ballot. |
| **Barangay Bagocboc Management System (BBMS)** | Web · Mobile app | A structured, three-tier digital platform designed to modernize, unify, and secure local governance, administrative workflows, and community health services within the barangay. The BBMS mobile application provides portability and smoothened accessibility for conducting household surveys, verifying resident profiles, and submitting certificate requests. |

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| WordPress | Content management system |
| PHP | Custom theme development, content types, contact form handling |
| HTML5 | Website structure and content |
| CSS3 | Black-and-gold styling, layout, animations and responsive design |
| JavaScript | Scroll intro, animations, filters, video playback and interactive features |
| Canvas API | Live-drawn cinematic scenes in the homepage intro |
| HTML5 Video (MP4) | Muted, looping demo videos inside browser, tablet and phone frames |
| Google Fonts | Instrument Sans and Outfit typefaces |
| Git | Version control and tracking project changes |
| GitHub | Repository hosting and team collaboration |
| [ADD LOCAL TOOL, e.g. LocalWP] | Local WordPress development environment |
| Hostinger | Website hosting and deployment |

## 📂 Project Structure

```
uncrown-portfolio/
├── style.css                    # Theme information and all site styling
├── functions.php                # Theme setup, content types, Customizer, contact form, skills, projects, social links
├── header.php                   # Floating header, logo and navigation (Home, Team, Process, Work)
├── footer.php                   # Footer links, social buttons and large logo
├── front-page.php               # Homepage: intro, team, skills chart, process, work and contact
├── index.php                    # Blog / fallback listing
├── page.php                     # Standard pages
├── page-contact.php             # Contact page with the routing form
├── single-ctp_member.php        # Team member profile with skill-level bars
├── single-ctp_project.php       # Project landing page with demo videos and key features
├── archive-ctp_project.php      # All projects with category filters
├── 404.php                      # "Page not found" page
├── readme.txt                   # Installation and usage notes
├── template-parts/
│   ├── story.php                # Cinematic scroll intro
│   ├── team-skills.php          # Team skills analytics chart
│   ├── skill-bars.php           # Skill-level bars for one member
│   ├── project-card.php         # Project card with hover-to-play demo video
│   └── device.php               # Browser, tablet and phone frames for demo videos
└── assets/
    ├── css/
    │   └── admin.css            # Dashboard widget styling
    ├── js/
    │   ├── scenes.js            # Live-drawn intro scenes (gold dust, orbits, crowns, arena, embers)
    │   ├── main.js              # Scroll engine, animations, filters, videos and interactions
    │   └── admin-media.js       # "Choose from Media Library" picker for project videos
    └── images/
        ├── uncrown-logo.png     # Full UNCROWN logo (transparent background)
        ├── uncrown-mark.png     # Crown-and-U emblem
        └── story-poster.jpg     # Still image for visitors who turn off motion
```

## 🗃️ Repository Layout

```
uncrown-portfolio/                      # this repository
├── .github/workflows/
│   └── deploy-hostinger.yml            # checks PHP, then uploads the theme to Hostinger over FTP
├── app/public/wp-content/
│   ├── themes/uncrown-portfolio/       # the WordPress theme (structure below)
│   └── uploads/2026/11/                # media: member photos, project demo videos, posters, screenshots
├── .gitignore                          # keeps WordPress core, plugins and resized image copies out of Git
└── README.md
```

The `app/public` path matches a LocalWP site, so the repository can sit directly in the LocalWP site folder.

The team members and projects themselves (names, roles, descriptions, skill levels, features) are stored in the WordPress database, not in files. The media they use is in `uploads/`:

| Project | Files in `uploads/2026/11/` |
|---|---|
| 5C Electrical Business Management System | `5c-web-dashboard.mp4`, `5c-pos-app.mp4`, `5c-delivery-app.mp4`, posters, `5c-screen-1…4.jpg` |
| eVote Campus Election System | `evote-web-app.mp4`, `evote-phone-app.mp4`, posters |
| Barangay Bagocboc Management System | `bbms-web-app.mp4`, `bbms-mobile-app.mp4`, posters, `bbms-web-*.jpg`, `bbms-mobile-*.jpg` |

## 🚀 Deployment

Every push to `main` that changes the theme runs the **Deploy theme to Hostinger** workflow. It checks the PHP files for errors, then uploads the theme folder to the live site over FTP. It needs four repository secrets (Settings → Secrets and variables → Actions):

| Secret | Value |
|---|---|
| `FTP_SERVER` | FTP host from Hostinger hPanel → Files → FTP Accounts |
| `FTP_USERNAME` | FTP username |
| `FTP_PASSWORD` | FTP password |
| `FTP_THEME_DIR` | `public_html/wp-content/themes/uncrown-portfolio/` |

## ⚙️ Theme Files and Functions

| File | Description |
|---|---|
| `functions.php` | Registers theme features and menus, loads CSS, JavaScript and fonts, creates the **Team Members** and **Projects** content types, adds Customizer settings (homepage text, team inbox, social links), handles the contact form, calculates team skill stats, and stores each project's demo videos, features and tech stack. |
| `front-page.php` | Builds the homepage: the scroll intro, team statistics, member cards, Team skills chart, process timeline, project gallery and contact section. |
| `single-ctp_project.php` | Turns each project into a landing page: a sliding title marquee, devices flying in with live demos, a pinned section that switches between apps, key features and tech stack. |
| `style.css` | Defines the visual design: black-and-gold colour tokens, typography, layout, device frames, animations and responsive styles. |
| `assets/js/scenes.js` | Draws the five cinematic intro scenes live in the browser using the Canvas API. |
| `assets/js/main.js` | Runs the scroll-driven intro, reveal animations, counters, 3D card tilt, project filters, demo-video playback, the contact form and navigation. |
| `template-parts/` | Reusable pieces: the intro, the skills chart, skill bars, project cards and device frames. |
| `assets/images/` | Stores the team logo, emblem and the intro's still image. |

## ✨ Key Features

- **Cinematic scroll intro:** a full-screen "film" that moves through five live-drawn scenes as you scroll, with headlines that build letter by letter and a gold particle warp between scenes, ending on the team logo, main headline and social links.
- **Team section:** member cards with photos, roles, short bios and each member's top three skills.
- **Team skills analytics:** a chart of the team's average level for each skill (e.g. Java, HTML, CSS, JavaScript, PHP), with a dot for every member, a hover breakdown and a table view.
- **Member profiles:** full bio, skill-level bars, skill tags and a button to message that member.
- **Process timeline:** Discover, Define, Build and Test & refine, drawn in as you scroll.
- **Project landing pages:** demo videos in browser, tablet and phone frames that fly together as you scroll, a pinned section that switches between each app, key-feature cards and a "Built with" list.
- **Project gallery:** a sideways-scrolling gallery on the homepage where each card plays its demo on hover, plus category filters.
- **Private contact routing:** visitors can message the whole team or one member; member email addresses are never shown publicly.
- **Social links:** Facebook, Instagram, X (Twitter) and GitHub buttons in the intro, Contact section and footer.
- **Easy editing:** team name, headlines, intro text, contact email and social links are all set in Appearance → Customize.
- **Accessibility:** keyboard-friendly navigation, a "Skip intro" button, a table view for the skills chart, and a calm still version for visitors who turn on "reduce motion".

## 💻 JavaScript Functionality

The custom JavaScript files enhance the user experience through the following features:

- **Scroll-Driven Intro:** Links scroll position to a camera fly-through of the intro scenes, smoothed so it feels filmed rather than scrolled.
- **Live Canvas Scenes:** Draws gold dust, orbit rings, crown peaks, an arena floor and rising embers in real time.
- **Letter-by-Letter Headlines:** Builds and dissolves each intro headline as the visitor scrolls.
- **Device Fly-In and App Switcher:** Brings the browser, tablet and phone frames together on scroll and switches the pinned device as each app's description comes into view.
- **Smart Video Playback:** Plays demo videos only while they are on screen, and on project cards only on hover.
- **Mobile Navigation:** Opens and closes a full-screen menu on smaller screens, including closing with the Escape key.
- **Smart Header:** Hides the header when scrolling down and shows it again when scrolling up; stays below the WordPress admin bar when logged in.
- **Scroll Progress Indicator:** Shows how far the visitor has scrolled through the page.
- **Active Navigation Highlighting:** Highlights the menu link for the section currently in view.
- **Reveal Animations and Counters:** Fades sections in as they appear, counts up statistics and grows skill bars.
- **3D Tilt and Magnetic Buttons:** Cards tilt toward the cursor and buttons gently follow it.
- **Project Filters:** Shows only the projects in the selected category.
- **Contact Form Recipient:** Shows the member list only when "Contact an Individual Member" is chosen.
- **Reduced Motion Support:** Turns off animations and autoplay for visitors who prefer less motion.

## 🚀 Development Progress

- [✓] Design the team logo and visual identity.
- [✓] Create the custom WordPress theme (Uncrown Portfolio).
- [✓] Build the Team Members and Projects content types.
- [✓] Develop the cinematic scroll intro and live-drawn scenes.
- [✓] Add member profiles and the Team skills analytics chart.
- [✓] Add the private contact form with member routing.
- [✓] Add Home navigation and social media links.
- [✓] Build project landing pages with demo videos in device frames.
- [✓] Prepare demo videos for 5C Electrical, eVote and BBMS.
- [✓] Test responsiveness on desktop and mobile layouts.
- [✓] Add every member's photo, bio and skill levels.
- [✓] Publish the three projects with their videos and features.
- [✓] Set up the GitHub repository structure and Hostinger deployment workflow.
- [✓] Push the repository to GitHub and add the FTP secrets.
- [x] Deploy the website to the hosting platform.
- [x] Perform final functionality testing across browsers.
- [x] Add contact email for each person/ group.
- [x] Add online contact site (fb,ig,discord and etc).


## 👥 Team Members

| Name | Role |
|---|---|
| Christopher Rhey Namoc | Project Manager |
| Laurence Dumaog | System Analyst |
| Goven Gil Sumaria | Programmer |
| Jonalyn Ihong | Technical Writer |

## 🔗 Repository

GitHub Repository:

**[ADD YOUR GITHUB REPOSITORY LINK HERE]**

## 🎓 Academic Project

**Course:** [ADD COURSE]

**Subject:** [ADD SUBJECT]

**Project:** WordPress Team Portfolio

**Developed by:** Team Uncrown
