# Quick Start Guide - Dashboard Pengurus

## 🚀 Memulai Dengan Dashboard Pengurus

### Prerequisites

- Node.js 16+
- npm atau yarn
- React Router DOM installed
- TailwindCSS configured
- lucide-react installed

---

## 📦 Installation & Setup

### 1. Install Dependencies (jika belum)

```bash
npm install lucide-react react-router-dom
```

### 2. Ensure TailwindCSS is configured

```bash
npm install -D tailwindcss
```

### 3. Import styles di main.css/index.css

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### 4. Project sudah ready!

Struktur folder sudah lengkap dan routing sudah dikonfigurasi di `App.jsx`

---

## 🔗 Mengakses Dashboard

### Via Browser

1. **Login Pengurus:**

   ```
   http://localhost:3000/pengurus/login
   ```

   - Email: (any email)
   - Password: (any password)
   - Role: Pilih "Pengurus Organisasi"

2. **Dashboard:**
   ```
   http://localhost:3000/pengurus
   ```

### Navigation Paths

```
/pengurus/           → Dashboard
/pengurus/event      → Event Management
/pengurus/pengumuman → Announcements
/pengurus/lost-found → Lost & Found
/pengurus/anggota    → Members
/pengurus/pendaftaran → Registrations
/pengurus/proposal   → Documents
/pengurus/pengaturan → Settings
```

---

## 🎮 Testing Features

### Dashboard (Main Page)

- ✅ View all stat cards
- ✅ Click quick action buttons
- ✅ Scroll to see recent lists

### Event Management

- ✅ List events by status filter
- ✅ Click "+ Buat Event" button
- ✅ Fill form dan click "Buat Event"
- ✅ View detail event
- ✅ Edit event (klik tombol Edit)
- ✅ Delete event (klik Hapus)
- ✅ Close registration (jika status open)

### Pengumuman

- ✅ List announcements
- ✅ Filter by status (Semua, Dipublikasikan, Draft)
- ✅ Buat pengumuman baru
- ✅ Edit pengumuman
- ✅ Publikasikan draft
- ✅ Delete pengumuman

### Lost & Found Moderation

- ✅ View all reported items
- ✅ Filter by type (Hilang/Ditemukan) & status
- ✅ Search items
- ✅ View detail & reporter contact
- ✅ Save internal notes
- ✅ Change status (Terbuka → Selesai → Arsip)

### Members Management

- ✅ List all members
- ✅ Filter by status (Aktif/Nonaktif)
- ✅ Search by name/NIM
- ✅ View member detail
- ✅ Change member position
- ✅ Deactivate/activate member

### Member Registrations

- ✅ View pending registrations
- ✅ Filter by status
- ✅ View registration detail
- ✅ Approve registration
- ✅ Reject registration

### Proposal & Archive

- ✅ View documents list
- ✅ Filter by type & status
- ✅ Upload new document
- ✅ Download document
- ✅ Approve draft document
- ✅ Delete document

### Organization Settings

- ✅ Edit description
- ✅ Edit vision & mission
- ✅ Edit contact info
- ✅ Edit social media links
- ✅ Upload logo (placeholder)
- ✅ Upload banner (placeholder)

---

## 💻 Code Examples

### Add New Data to Dashboard

```javascript
// In component state:
const [events, setEvents] = useState([
  {
    id: 1,
    name: 'New Event',
    date: '2026-03-15',
    time: '14:00',
    location: 'Ruang Rapat',
    quota: 100,
    participants: 0,
    status: 'draft',
    description: 'Event description here',
  },
  // ... more events
]);
```

### Handle Form Submit

```javascript
const handleSave = () => {
  // Validate required fields
  if (!formData.name || !formData.date) {
    alert('Tolong isi semua field');
    return;
  }

  // Create or update
  if (editingId) {
    setItems(
      items.map((item) =>
        item.id === editingId ? { ...item, ...formData } : item
      )
    );
  } else {
    setItems([...items, { id: Date.now(), ...formData }]);
  }

  // Reset & close
  setShowForm(false);
};
```

### Filter & Search Implementation

```javascript
const filteredItems = items.filter((item) => {
  let match = true;

  // Status filter
  if (statusFilter !== 'all' && item.status !== statusFilter) {
    match = false;
  }

  // Search filter
  if (
    searchQuery &&
    !item.name.toLowerCase().includes(searchQuery.toLowerCase())
  ) {
    match = false;
  }

  return match;
});
```

### Modal State Management

```javascript
const [showForm, setShowForm] = useState(false);
const [selectedItem, setSelectedItem] = useState(null);

const openCreate = () => {
  setFormData({
    /* empty form */
  });
  setEditingId(null);
  setShowForm(true);
};

const openEdit = (item) => {
  setFormData(item);
  setEditingId(item.id);
  setShowForm(true);
};

const closeModal = () => {
  setShowForm(false);
  setSelectedItem(null);
};
```

---

## 🔧 Customization Guide

### Change Primary Color

1. Update BurgerMenu.jsx active button color
2. Update all button backgrounds
3. Update badge colors

Search & Replace:

```
bg-blue-100 → bg-[your-color]-100
text-blue-700 → text-[your-color]-700
border-blue-300 → border-[your-color]-300
```

### Add New Event Status

```javascript
// In Event.jsx, update status filter:
const statusOptions = [
  'all',
  'open',
  'closed',
  'draft',
  'cancelled',
  'postponed',
];

// Update badge styling for new status
```

### Modify Form Fields

```javascript
// In form sections, simply add new input fields:
<div>
  <label>New Field Label</label>
  <input
    value={formData.newField}
    onChange={(e) => setFormData({ ...formData, newField: e.target.value })}
    className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg..."
  />
</div>
```

---

## 🐛 Troubleshooting

### Issue: Burger menu tidak muncul

- Check if Header component properly calls `onMenuClick`
- Check z-index in BurgerMenu CSS

### Issue: Modal tidak tertutup

- Ensure `setShowForm(false)` dipanggil setelah submit
- Check onClick handlers pada close button

### Issue: Filter tidak bekerja

- Verify filterState value matches data property
- Check if data array kosong

### Issue: Icons tidak muncul

- Ensure `lucide-react` installed: `npm install lucide-react`
- Check component import: `import { IconName } from 'lucide-react'`

---

## 📈 Performance Tips

### Optimize Large Lists

```javascript
// Use useMemo for filtered results
const filteredItems = useMemo(() => {
  return items.filter(/* ... */);
}, [items, filterState, searchQuery]);
```

### Lazy Load Components

```javascript
// Already done in App.jsx with:
const PengurusDashboard = lazy(() => import('./pages/pengurus/Dashboard'));
```

### Minimize Re-renders

```javascript
// Use useCallback for event handlers
const handleSave = useCallback(() => {
  // handler logic
}, [dependencies]);
```

---

## 🔐 Security Reminders

- ✅ All pages wrapped in `ProtectedRoute`
- ✅ Role validation in `ProtectedRoute` component
- ⚠️ Dummy data = Replace with actual API
- ⚠️ File uploads = Implement server validation
- ⚠️ Sensitive info = Use environment variables

---

## 📚 File Structure Review

```
src/
├── pages/pengurus/
│   ├── Dashboard.jsx ................. Main dashboard
│   ├── Event.jsx ..................... Event management
│   ├── Pengumuman.jsx ................ Announcements
│   ├── LostFound.jsx ................. L&F moderation
│   ├── Anggota.jsx ................... Members
│   ├── Pendaftaran.jsx ............... Registrations
│   ├── Proposal.jsx .................. Documents
│   ├── Pengaturan.jsx ................ Settings
│   ├── ProfilOrganisasi.jsx .......... Org profile
│   ├── PengajuanLaporan.jsx .......... Submissions
│   └── Login.jsx ..................... Login page
├── layouts/
│   ├── PengurusLayout.jsx ............ Layout wrapper
│   └── ... (other layouts)
├── components/layout/
│   ├── BurgerMenu.jsx ................ Side nav
│   ├── Header.jsx .................... Header
│   └── ... (other components)
└── App.jsx ........................... Routing config
```

---

## 🚀 Deployment Checklist

- [ ] Replace dummy data dengan API
- [ ] Add environment variables
- [ ] Add error boundaries
- [ ] Add loading states
- [ ] Add success messages
- [ ] Implement proper auth
- [ ] Add form validation
- [ ] Test responsive design
- [ ] Test all routes
- [ ] Build & minify
- [ ] Deploy to server

---

## 📞 Support

Untuk questions atau issues:

1. Check component documentation in file headers
2. Review state management patterns
3. Check browser console for errors
4. Verify all dependencies installed

---

**Dashboard Pengurus - Ready to Use!** ✅

Selamat menggunakan Dashboard Pengurus Organisasi Mahasiswa versi lengkap!
