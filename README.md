# 👑 Uncrown Portfolio

> No titles. Only the work.

A custom WordPress theme and team portfolio for **Team Uncrown**, built for our **Cloud Computing** course in the **BSIT** program. It presents who we are, how we work and the systems we have shipped, and it runs live on Hostinger.

🔗 **Live site:** (https://earthy-tan-giraffe-34.hostingersite.com/)
📁 **Repository:** [github.com/Ads091/uncrown-portfolio](https://github.com/Ads091/uncrown-portfolio)

---

## 📖 Contents

1. [About the Project](#about-the-project)
2. [What's on the Site](#whats-on-the-site)
3. [Our Systems](#our-systems)
4. [Built With](#built-with)
5. [Folder Structure](#folder-structure)
6. [Run It Locally](#run-it-locally)
7. [Editing the Site](#editing-the-site)
8. [Deployment Pipeline](#deployment-pipeline)
9. [Progress](#progress)
10. [The Team](#the-team)

---

## About the Project

Most student portfolios are a list of names and a few screenshots. We wanted ours to feel like a product launch. The site opens with a short film you control by scrolling, then introduces each member, our process and the systems we built, each one with its own page and live demo videos.

The theme, **Uncrown Portfolio**, was written from scratch for this project. Its black, gold and silver look comes straight from our logo: the crown, the ring around the "U" and the gold lettering appear throughout the design.

**Goals**

- Build a responsive, accessible portfolio that works on phones and desktops.
- Show each member's role, profile and skill levels.
- Present our systems the way real products are presented: with demos, features and the tools used.
- Let visitors contact the whole team or one member without exposing anyone's email.
- Keep the code in GitHub and deploy it to live hosting automatically.

---

## What's on the Site

| Section | What visitors see |
|---|---|
| **Intro** | A full-screen scroll film in five scenes (gold dust, orbit rings, crown peaks, an arena floor, rising embers), drawn live in the browser. Headlines build letter by letter, and it ends on our logo, headline and social links. A **Skip intro** button jumps to the end. |
| **Team** | A card for each member with photo, role, short bio and top skills, plus a **Team skills** chart showing the team average for each skill and every member's level. |
| **Process** | Our four steps (Discover, Define, Build, Test & refine) on a timeline that draws itself as you scroll. |
| **Work** | A sideways-scrolling gallery of our systems. Each card plays its demo when you hover over it. |
| **Project pages** | A landing page per system: the name sliding across as a giant title, browser, tablet and phone mockups flying in with live demos, a section that switches between each app, then key features and tech stack. |
| **Member pages** | Full bio, skill-level bars, skill tags and a button to message that person. |
| **Contact** | One form that reaches the whole team or a chosen member. Messages are routed on the server, so no email address appears on the site. |

Everything respects the visitor's **reduce motion** setting: they get a calm, still version with no autoplay.

---

## Our Systems

### ⚡ 5C Electrical Business Management System
**Web dashboard · POS tablet app · Delivery phone app**
One system for 5C Electrical. Owners track sales goals, trends, profit and stock on the web dashboard; cashiers ring up sales on the POS tablet; drivers follow routes and capture proof of delivery on their phones. All three share the same sales and stock data.

### 🗳️ eVote Campus Election System
**Admin web console · Voter mobile app**
The election committee manages candidates, watches turnout and live results, and finalizes the election from the console. Students sign in on their phones, vote once per position and receive a locked ballot receipt.

### 🏘️ Barangay Bagocboc Management System (BBMS)
**Web system · Mobile app**
A structured, three-tier digital platform designed to modernize, unify, and secure local governance, administrative workflows, and community health services within the barangay. The mobile app provides portability and smoothened accessibility for conducting household surveys, verifying resident profiles, and submitting certificate requests.

---

## Built With

**Site:** WordPress · PHP · HTML5 · CSS3 · JavaScript · Canvas API · HTML5 video
**Fonts:** Instrument Sans and Outfit (Google Fonts)
**Workflow:** Git · GitHub · GitHub Actions · LocalWP
**Hosting:** Hostinger

The animations are written in plain JavaScript with no external animation libraries, so nothing extra loads from other sites.

---

## Folder Structure

```
uncrown-portfolio/
├── .github/
│   └── workflows/
│       └── deploy-hostinger.yml     # Checks PHP, then uploads the theme to Hostinger
├── .gitignore                       # Tracks only our theme and original media
├── README.md
└── app/
    └── public/
        └── wp-content/
            ├── themes/
            │   └── uncrown-portfolio/
            │       ├── style.css                 # All styling and the theme's details
            │       ├── functions.php             # Content types, settings, contact form, skills, projects
            │       ├── header.php / footer.php   # Navigation, social buttons, logo
            │       ├── front-page.php            # The homepage, section by section
            │       ├── single-ctp_member.php     # A member's profile page
            │       ├── single-ctp_project.php    # A system's landing page
            │       ├── archive-ctp_project.php   # All systems, with filters
            │       ├── page-contact.php          # The contact form page
            │       ├── page.php / index.php / 404.php
            │       ├── readme.txt                # Install notes for WordPress
            │       ├── teamprofile/              # One file per member: role, bio, description, skills
            │       ├── template-parts/           # Intro, skills chart, skill bars, project cards, device frames
            │       └── assets/
            │           ├── js/                   # scenes.js (intro), main.js (motion), admin-media.js (video picker)
            │           ├── css/                  # Dashboard styling
            │           └── images/               # Logo, emblem, intro still
            └── uploads/
                └── 2026/11/                      # Member photos, demo videos, posters, screenshots
```

The `app/public` path mirrors a LocalWP site, so the repository can be cloned straight into one.

Members and projects live in the **WordPress database**. A copy of each member's details (role, bio, description, skills and skill levels, without emails) is kept in `teamprofile/`; download or reload it from **Team Members → Team Profiles**. Photos and videos are in `uploads/`.

---

## Run It Locally

1. Install [LocalWP](https://localwp.com/) and create a new WordPress site.
2. Clone this repository into the site's folder, or copy `app/public/wp-content/themes/uncrown-portfolio` into the site's `themes` folder:
   ```bash
   git clone https://github.com/Ads091/uncrown-portfolio.git
   ```
3. In WP Admin, go to **Appearance → Themes** and activate **Uncrown Portfolio**.
4. Go to **Settings → Permalinks** and click **Save Changes** once so project pages open.
5. Add members, projects and settings (see below).

---

## Editing the Site

No code is needed for day-to-day changes. Everything is in the WordPress dashboard:

- **Appearance → Customize → Uncrown Homepage:** team name, headlines, intro text, team email
- **Appearance → Customize → Uncrown Social Links:** Facebook, Instagram, X, GitHub
- **Team Members:** photo, role, bio, skills, skill levels (`Java: 80`, one per line), private email
- **Team Members → Team Profiles:** download members as files for GitHub, or load the files back into the site
- **Projects:** description, up to three demo videos (web, tablet, phone), key features, tech stack
- **Media:** images and videos

---

## Deployment Pipeline

```
Push to main  →  GitHub Actions checks the PHP  →  uploads the theme to Hostinger over FTP  →  live site updated
```

The workflow runs whenever theme files change on `main`, or by hand from the **Actions** tab. It reads the FTP login from repository secrets, so no password is stored in the code:

`FTP_SERVER` · `FTP_USERNAME` · `FTP_PASSWORD` · `FTP_THEME_DIR` (`public_html/wp-content/themes/uncrown-portfolio/`)

---

## Progress

**Done**
- [x] Logo and visual identity
- [x] Custom theme with the scroll intro, team, process, work and contact sections
- [x] Member profiles and the Team skills chart
- [x] Project landing pages with demo videos for 5C, eVote and BBMS
- [x] Private contact form with member routing
- [x] Responsive layout tested on desktop and mobile
- [x] Member photos, bios, skill levels and the three projects published
- [x] GitHub repository and Hostinger deployment workflow

**Still to do**
- [ ] Final deployment to the hosting platform
- [ ] Final testing across browsers
- [ ] Team inbox and each member's contact email
- [ ] Social media links (Facebook, Instagram, X, GitHub)

---

## The Team

| | Name | Role |
|---|---|---|
| 👑 | **Christopher Rhey Namoc** | Project Manager |
| 🧭 | **Laurence Dumaog** | System Analyst |
| 💻 | **Goven Gil Sumaria** | Programmer |
| ✍️ | **Jonalyn Ihong** | Technical Writer |

---

<p align="center"><b>Team Uncrown</b> · BSIT · Cloud Computing · 2026</p>
