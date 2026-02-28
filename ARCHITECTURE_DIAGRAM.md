/\*\*

- COMPONENT ARCHITECTURE DIAGRAM
-
- UFO Burger Menu Implementation Structure
  \*/

/\*
╔════════════════════════════════════════════════════════════════════════════╗
║ APP (src/App.jsx) ║
║ ║
║ ┌──────────────────────────────────────────────────────────────────────┐ ║
║ │ <BrowserRouter> │ ║
║ │ <Routes> │ ║
║ │ │ ║
║ │ ┌─── PUBLIC ROUTES ────────────────────────────────────────────┐ │ ║
║ │ │ <Route element={<MahasiswaLayout />}> │ │ ║
║ │ │ - path: "/" (Home) │ │ ║
║ │ │ - path: "/organisasi" (Organisasi) │ │ ║
║ │ │ - path: "/event" (Event) │ │ ║
║ │ │ - path: "/pengumuman" (Pengumuman) │ │ ║
║ │ │ - path: "/lost-found" (LostAndFound) ← NEW │ │ ║
║ │ │ - path: "/tentang-ufo" (TentangUFO) ← NEW │ │ ║
║ │ └────────────────────────────────────────────────────────────────┘ │ ║
║ │ │ ║
║ │ ┌─── PROTECTED ROUTES ──────────────────────────────────────────┐ │ ║
║ │ │ Pengurus, Admin, Kemahasiswaan... │ │ ║
║ │ └────────────────────────────────────────────────────────────────┘ │ ║
║ │ </Routes> │ ║
║ │ </BrowserRouter> │ ║
║ └──────────────────────────────────────────────────────────────────────┘ ║
╚════════════════════════════════════════════════════════════════════════════╝

┌─────────────────────────────────────────────────────────────────────────────┐
│ MahasiswaLayout (NEW) │
│ src/layouts/MahasiswaLayout.jsx │
├─────────────────────────────────────────────────────────────────────────────┤
│ │
│ ┌───────────────────────────────────────────────────────────────────────┐ │
│ │ State Management: │ │
│ │ - const [menuOpen, setMenuOpen] = useState(false) │ │
│ │ - const [activeItem, setActiveItem] = useState(null) │ │
│ └───────────────────────────────────────────────────────────────────────┘ │
│ │
│ ┌──── RENDER ────────────────────────────────────────────────────────┐ │
│ │ │ │
│ │ 1. <Header /> │ │
│ │ ├─ role="mahasiswa" │ │
│ │ ├─ onMenuClick={() => setMenuOpen(true)} │ │
│ │ └─ Hamburger button (☰) │ │
│ │ │ │
│ │ 2. <StudentBurgerMenu /> ← NEW COMPONENT │ │
│ │ ├─ open={menuOpen} │ │
│ │ ├─ onClose={() => setMenuOpen(false)} │ │
│ │ └─ activeItem={activeItem} │ │
│ │ │ │
│ │ 3. <main className="mahasiswa-layout-content"> │ │
│ │ └─ <Outlet /> ← renders page content │ │
│ │ │ │
│ └────────────────────────────────────────────────────────────────────┘ │
│ │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ StudentBurgerMenu Component (NEW) │
│ src/components/layout/StudentBurgerMenu.jsx │
├─────────────────────────────────────────────────────────────────────────────┤
│ │
│ Props: │
│ ├─ open (boolean) - kontrol pembukaan/penutupan │
│ ├─ onClose (function) - callback close menu │
│ └─ activeItem (string) - track item yang aktif │
│ │
│ Structure: │
│ ├─ .student-menu-overlay │
│ │ └─ onClick → handleOverlayClick() → onClose() │
│ │ │
│ └─ .student-burger-menu │
│ ├─ .student-burger-header │
│ │ ├─ Icon (🛸) │
│ │ ├─ Title ("Menu UFO") │
│ │ └─ Close Button (×) │
│ │ └─ onClick → onClose() │
│ │ │
│ ├─ .student-burger-nav │
│ │ └─ ul.student-burger-list │
│ │ ├─ li.student-burger-item │
│ │ │ └─ Link to="/organisasi" │
│ │ │ └─ onClick → onClose() │
│ │ ├─ li.student-burger-item │
│ │ │ └─ Link to="/event" │
│ │ ├─ li.student-burger-item │
│ │ │ └─ Link to="/lost-found" ← NEW │
│ │ ├─ li.student-burger-item │
│ │ │ └─ Link to="/pengumuman" │
│ │ └─ li.student-burger-item │
│ │ └─ Link to="/tentang-ufo" ← NEW │
│ │ │
│ └─ .student-burger-footer │
│ ├─ "UNKLAB Forum Organization" │
│ └─ "Versi 1.0" │
│ │
│ CSS Classes: │
│ ├─ .student-burger-menu (main container) │
│ ├─ .student-burger-menu--open (modifier) │
│ ├─ .student-burger-link (menu item) │
│ ├─ .student-burger-link--active (active state) │
│ ├─ .student-menu-overlay (dimmed background) │
│ └─ ... (15+ more classes) │
│ │
│ Animations: │
│ ├─ Slide-in: transform: translateX(-110% → 0) │
│ ├─ Duration: 0.28s cubic-bezier(0.4, 0, 0.2, 1) │
│ └─ Overlay fade: opacity 0 → 1, 0.3s ease-in-out │
│ │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ Header Component (UPDATED) │
│ src/components/layout/Header.jsx │
├─────────────────────────────────────────────────────────────────────────────┤
│ │
│ Props: │
│ ├─ role (string) - "mahasiswa" | "pengurus" | "admin" | "kemahasiswaan" │
│ └─ onMenuClick (function) - trigger burger menu │
│ │
│ Features: │
│ ├─ Role-based styling (colors, gradients) │
│ ├─ Hamburger button (SVG icon) │
│ ├─ UFO icon (🛸) + brand text │
│ └─ Welcome text / notification │
│ │
│ For Mahasiswa Role: │
│ ├─ Background: gradient-to-r from-purple-700 to-purple-800 │
│ ├─ Text: white │
│ └─ Hamburger: hover bg-white bg-opacity-20 │
│ │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ New Pages (NEW) │
├─────────────────────────────────────────────────────────────────────────────┤
│ │
│ 1. LostAndFound.jsx (src/pages/public/LostAndFound.jsx) │
│ - Mock data with Lost/Found items │
│ - Status badges (HILANG / DITEMUKAN) │
│ - Report button │
│ │
│ 2. TentangUFO.jsx (src/pages/public/TentangUFO.jsx) │
│ - UFO logo & title │
│ - Description & features │
│ - Visi & misi │
│ - App info (version, status) │
│ - Contact info │
│ │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ Configuration & Helpers (NEW) │
├─────────────────────────────────────────────────────────────────────────────┤
│ │
│ 1. menuConfig.js (src/config/menuConfig.js) │
│ ├─ STUDENT_MENU_ITEMS (array) │
│ │ ├─ { id, label, icon, path, description } │
│ │ └─ 5 menu items │
│ ├─ APP_INFO (object) │
│ │ ├─ { name, version, year, status } │
│ │ └─ UNKLAB Forum Organization v1.0 │
│ ├─ STYLE_CONFIG (object) │
│ │ ├─ colors (primary, accent, text, etc) │
│ │ ├─ sizing (sidebarWidth, headerHeight, etc) │
│ │ └─ timing (slideTransition, fadeTransition, etc) │
│ └─ LAYOUT_CONFIG (object) │
│ └─ Per-role styling config │
│ │
│ 2. useMenuNavigation.js (src/hooks/useMenuNavigation.js) │
│ ├─ useKeyboardNavigation(open, onClose) │
│ │ └─ Esc key to close menu │
│ ├─ useFocusManagement(open, sidebarRef) │
│ │ └─ Accessibility focus management │
│ ├─ menuReducer(state, action) │
│ │ └─ Complex state management pattern │
│ └─ useOutsideClick(ref, callback) │
│ └─ Click outside detection │
│ │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ CSS Structure │
│ (public/css/app.css - NEW SECTION) │
├─────────────────────────────────────────────────────────────────────────────┤
│ │
│ Selectors: │
│ ├─ .student-menu-overlay (dark overlay background) │
│ ├─ .student-burger-menu (sidebar container - 280px) │
│ ├─ .student-burger-menu.student-burger-menu--open (slide-in state) │
│ ├─ .student-burger-header (header area with icon & close) │
│ ├─ .student-burger-title (Menu UFO title) │
│ ├─ .student-burger-close (X close button) │
│ ├─ .student-burger-nav (scrollable menu container) │
│ ├─ .student-burger-list (ul element) │
│ ├─ .student-burger-item (li element) │
│ ├─ .student-burger-link (menu item link) │
│ ├─ .student-burger-link:hover (hover state) │
│ ├─ .student-burger-link--active (active state - UNGU BG) │
│ ├─ .student-burger-icon-item (emoji icon in menu) │
│ ├─ .student-burger-divider (optional divider) │
│ ├─ .student-burger-footer (footer area) │
│ ├─ .student-burger-footer-title (UNKLAB text) │
│ └─ .student-burger-footer-version (v1.0 text) │
│ │
│ Keyframes: │
│ └─ @keyframes fadeIn (overlay fade animation) │
│ │
│ Colors: │
│ ├─ Sidebar BG: linear-gradient(135deg, #ffcc00, #ffb800) │
│ ├─ Primary: #663399 (ungu) │
│ ├─ Overlay: rgba(0, 0, 0, 0.45) │
│ └─ Text: #111 (dark) │
│ │
│ Layout: │
│ ├─ .mahasiswa-layout (main wrapper) │
│ └─ .mahasiswa-layout-content (main content area) │
│ │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ USER INTERACTION FLOW │
├─────────────────────────────────────────────────────────────────────────────┤
│ │
│ Flow 1: Open Menu │
│ ┌─────────────────────────────────────────────────────────────────────┐ │
│ │ 1. User sees header with hamburger button (☰) │ │
│ │ 2. User clicks hamburger button │ │
│ │ 3. onMenuClick() triggered → setMenuOpen(true) │ │
│ │ 4. StudentBurgerMenu receives open={true} │ │
│ │ 5. CSS: .student-burger-menu--open applied │ │
│ │ 6. Transform: translateX(-110%) → translateX(0) │ │
│ │ 7. .student-menu-overlay appears (fadeIn animation) │ │
│ │ 8. Menu visible with smooth animation (0.28s) │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│ │
│ Flow 2: Close via Close Button │
│ ┌─────────────────────────────────────────────────────────────────────┐ │
│ │ 1. User clicks X button in header │ │
│ │ 2. onClick → onClose() callback │ │
│ │ 3. setMenuOpen(false) │ │
│ │ 4. StudentBurgerMenu receives open={false} │ │
│ │ 5. --open class removed │ │
│ │ 6. Transform: translateX(0) → translateX(-110%) │ │
│ │ 7. Overlay fades out │ │
│ │ 8. Menu slides out (0.28s) │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│ │
│ Flow 3: Close via Overlay Click │
│ ┌─────────────────────────────────────────────────────────────────────┐ │
│ │ 1. User clicks dark overlay area │ │
│ │ 2. handleOverlayClick(e) triggered │ │
│ │ 3. onClose() callback │ │
│ │ 4. Same as Close via Close Button (steps 3-8) │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│ │
│ Flow 4: Navigate from Menu │
│ ┌─────────────────────────────────────────────────────────────────────┐ │
│ │ 1. User clicks menu item (e.g., "Organisasi") │ │
│ │ 2. Link component triggers navigation │ │
│ │ 3. onClick → onClose() callback (in Link) │ │
│ │ 4. setMenuOpen(false) │ │
│ │ 5. Menu closes while navigating │ │
│ │ 6. Page changes to /organisasi │ │
│ │ 7. New page renders under MahasiswaLayout │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│ │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ STYLING BREAKDOWN │
├─────────────────────────────────────────────────────────────────────────────┤
│ │
│ Sidebar Styling: │
│ ┌─────────────────────────────────────────────────────────────────────┐ │
│ │ Width: 280px (standard mobile sidebar) │ │
│ │ Background: linear-gradient(135deg, #ffcc00 0%, #ffb800 100%) │ │
│ │ Position: fixed (left: 0, top: 0, bottom: 0) │ │
│ │ Z-index: 96 │ │
│ │ Transform: translateX(-110%) → translateX(0) │ │
│ │ Transition: 0.28s cubic-bezier(0.4, 0, 0.2, 1) │ │
│ │ Overflow: overflow-y auto (scrollable if needed) │ │
│ │ Display: flex flex-direction-column │ │
│ │ Box-shadow: var(--shadow-lg) │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│ │
│ Header Styling: │
│ ┌─────────────────────────────────────────────────────────────────────┐ │
│ │ Background: rgba(102, 51, 153, 0.08) (transparent purple) │ │
│ │ Border-bottom: 2px solid rgba(102, 51, 153, 0.2) │ │
│ │ Display: flex justify-between items-center │ │
│ │ Padding: 16px │ │
│ │ Flex-shrink: 0 │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│ │
│ Menu Items Styling: │
│ ┌─────────────────────────────────────────────────────────────────────┐ │
│ │ Default: │ │
│ │ - Color: #111 (text-primary) │ │
│ │ - Padding: 12px 16px │ │
│ │ - Display: flex gap-12 │ │
│ │ - Border-left: 4px transparent │ │
│ │ │ │
│ │ Hover: │ │
│ │ - Background: rgba(102, 51, 153, 0.1) │ │
│ │ - Border-left-color: #663399 │ │
│ │ - Transition: all 0.2s ease │ │
│ │ │ │
│ │ Active (.student-burger-link--active): │ │
│ │ - Background: #663399 (UNGU FULL) │ │
│ │ - Color: white │ │
│ │ - Border-left-color: white │ │
│ │ - Font-weight: 500 │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│ │
│ Footer Styling: │
│ ┌─────────────────────────────────────────────────────────────────────┐ │
│ │ Background: rgba(102, 51, 153, 0.08) │ │
│ │ Border-top: 2px solid rgba(102, 51, 153, 0.2) │ │
│ │ Padding: 16px │ │
│ │ Text-align: center │ │
│ │ Margin-top: auto (stick to bottom) │ │
│ │ Flex-shrink: 0 │ │
│ └─────────────────────────────────────────────────────────────────────┘ │
│ │
└─────────────────────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════════════
KEY FILES SUMMARY
═══════════════════════════════════════════════════════════════════════════════

Core Components:
✓ StudentBurgerMenu.jsx (~200 lines) Main component
✓ MahasiswaLayout.jsx (~50 lines) Layout wrapper
✓ Header.jsx (~80 lines) Updated with hamburger

New Pages:
✓ LostAndFound.jsx (~70 lines) Placeholder page
✓ TentangUFO.jsx (~100 lines) About page

Config & Helpers:
✓ menuConfig.js (~80 lines) Menu configuration
✓ useMenuNavigation.js (~100 lines) Custom hooks

Styling:
✓ app.css (+150 lines) Complete CSS

Documentation:
✓ BURGER_MENU_README.md (~300 lines) Feature overview
✓ BURGER_MENU_IMPLEMENTATION.md (~600 lines) Technical docs
✓ QUICK_START.md (~200 lines) Quick reference

═══════════════════════════════════════════════════════════════════════════════
\*/

// VISUAL MENU STRUCTURE:
//
// Header (height: 56px)
// ├─ Hamburger (☰) ──────────────────── UFO 🛸 ──────────────────── Welcome
// │
// Sidebar (width: 280px) ← SLIDES-IN from left
// ├─ 🛸 Menu UFO [X] ← Header
// │
// ├─ 👥 Organisasi
// ├─ 📅 Event
// ├─ 🔍 Lost & Found
// ├─ 📢 Pengumuman
// ├─ ℹ️ Tentang UFO
// │
// └─ UNKLAB Forum Organization ← Footer
// Versi 1.0
//
// [Overlay: semi-transparent black]
