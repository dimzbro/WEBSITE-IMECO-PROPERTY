# Beltway Office Park - Website Portal

A premium and highly responsive web application built with **Laravel** and **Vite (TailwindCSS / CSS)**, representing the commercial real estate development in TB Simatupang Business District, South Jakarta.

---

## 🚀 Features & Modules

### 1. Hero Landing Section
- **Premium Dark Gradient Overlay**: Features a smooth horizontal gradient (`to right`) of Navy Blue (`#0F172A`) with high opacity on the left (`98%`) fading to a lighter opacity (`58%`) on the right. This optimizes reading contrast for the white headlines while showing the building texture on the right.
- **Clean Interface**: Removed the cluttering top real estate badge for a modern, sleek presentation.

### 2. Neat Available Spaces Grid
- **Uniform Height Cards**: Integrated a clean flexbox layout in `app.css` to stretch all cards to identical heights regardless of text lengths.
- **Pinned Bottom Buttons**: Utilized `mt-auto` on the buttons container inside each card body so that the "View Floor Plan" and "Request Info" buttons are perfectly aligned horizontally.
- **Responsive Layout**: Designed a responsive grid system (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8`) for seamless display on mobile, tablet, and desktop screens.
- **Filters Wrap**: Made the filtering buttons wrapper responsive (`flex-wrap`) to prevent overflow on narrow screens.

### 3. News & Articles Toggle Action
- **Interactive Staggered Show/Hide**: Implemented a smooth JavaScript toggle that allows users to click the **"View All Articles"** button to reveal 3 additional articles (staggered delay animation). Clicking it again as **"Show Less"** collapses the list and smoothly scrolls the user back to the top of the news section.
- **Minimalist Presentation**: Removed the duplicate "Read More" button cards to keep the article snippets neat.

### 4. Hidden Admin Entry Points
To maintain a clean and direct client-facing portal, the admin login page is accessible through **three secret triggers**:
1. **Logo Double-Click**: Double-click the **BELTWAY** logo in the top sticky navbar.
2. **Keyboard Shortcut**: Press `Ctrl + Shift + L` simultaneously anywhere on the home page.
3. **Secret Footer Link**: Click the invisible 1px dot (`.`) located directly after the *"All Rights Reserved."* copyright text in the footer.

### 5. Protected Admin Page
- **Redirection**: Successful logins automatically redirect the admin to the `/admin` dashboard.
- **Session Bar**: An elegant Administrator utility bar is displayed at the top of the homepage when logged in, featuring:
  - An active session green pulsing indicator.
  - A **"Go to Dashboard"** link pointing to the admin page.
  - A secure **"Logout"** post action.
- **Layout Shifting**: The page body shifts down by `36px` to cleanly accommodate the top bar without overlapping the main navbar.

---

## 🎨 Color Style Guidelines

The brand uses a strict, premium two-color theme:
- **Navy Blue (`#0F172A`)** - Used for dark backgrounds, overlay backings, main headings, footers, and interactive hover backdrops.
- **Royal Blue (`#1E3A8A`)** - Used for interactive elements, CTA buttons, active state borders, icons, and focus outlines.

---

## 🔐 Credentials & DB Seeding

The application implements database-backed authentication using Laravel's native session guard (`Auth::attempt`).

### Admin Login Credentials
- **Username / Email**: `admin@beltwayofficepark`
- **Password**: `bopadmin`

### Database Setup (MySQL)
The project is configured to run on MySQL with the database `beltway_db`.

1. Ensure MySQL is running on your machine.
2. Create the database in MySQL:
   ```sql
   CREATE DATABASE beltway_db;
   ```
3. Run the migrations and database seeder to seed the admin account:
   ```bash
   php artisan migrate --seed
   ```

---

## 💻 Local Development

### 1. Clone & Set Up environment
Copy the environment variables:
```bash
cp .env.example .env
```
Ensure your database credentials are correct in `.env`.

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Generate Key
```bash
php artisan key:generate
```

### 4. Build Assets
Compile client styles and scripts with Vite:
```bash
npm run build
```

### 5. Serve Project
Run the PHP CLI server:
```bash
php artisan serve
```
Open [http://localhost:8000](http://localhost:8000) in your browser.

---

## 📂 Project Architecture Highlights

- **Views**:
  - Main Homepage: [home.blade.php](file:///c:/Users/Lenovo/beltway-office-park/resources/views/home.blade.php)
  - Layout Base: [layouts/app.blade.php](file:///c:/Users/Lenovo/beltway-office-park/resources/views/layouts/app.blade.php)
  - Login Card: [auth/login.blade.php](file:///c:/Users/Lenovo/beltway-office-park/resources/views/auth/login.blade.php)
  - Simple Admin view: [admin/dashboard.blade.php](file:///c:/Users/Lenovo/beltway-office-park/resources/views/admin/dashboard.blade.php)
- **Styles**:
  - Theme Stylesheet: [app.css](file:///c:/Users/Lenovo/beltway-office-park/resources/css/app.css)
- **Routes**:
  - Web Routes: [web.php](file:///c:/Users/Lenovo/beltway-office-park/routes/web.php)
- **Database**:
  - Database Seeder: [DatabaseSeeder.php](file:///c:/Users/Lenovo/beltway-office-park/database/seeders/DatabaseSeeder.php)
