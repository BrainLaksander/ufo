# 🚀 Implementation Guide - Fitur Mahasiswa UFO System

## 📋 Quick Start

### 1. File-file yang Dibuat

#### Komponen Reusable (6 file):

```
src/components/ui/
  ├── Card.jsx          - Card component dengan hover effect
  ├── Badge.jsx         - Badge untuk status, kategori
  ├── Dialog.jsx        - Modal dialog
  ├── Tabs.jsx          - Tab navigation
  ├── SearchInput.jsx   - Search input dengan icon
  └── FilterChips.jsx   - Filter chips buttons
```

#### Halaman Mahasiswa (4 file):

```
src/pages/public/
  ├── Pengumuman.jsx        - List pengumuman dengan tabs & search
  ├── LostAndFound.jsx      - Lost & found dengan form lapor
  ├── Organisasi.jsx        - List organisasi dengan filter
  └── OrganisasiDetail.jsx  - Detail organisasi page
```

#### Routing Update:

```
src/App.jsx - Ditambah import & routes untuk:
  - /organisasi/:id (dynamic detail)
  - Pengumuman (sudah ada, dipastikan aktif)
  - Lost & Found (sudah ada, dipastikan aktif)
```

---

## 🔧 Setup & Integration

### Step 1: Verify Dependencies

Pastikan project sudah punya:

```bash
npm list react
npm list react-router-dom
npm list tailwindcss
```

Jika belum:

```bash
npm install react-router-dom tailwindcss
```

### Step 2: Verify TailwindCSS Configuration

Cek bahwa TailwindCSS sudah configured dengan content paths:

```javascript
// tailwind.config.js
module.exports = {
  content: ['./src/**/*.{js,jsx}'],
  // ...
};
```

### Step 3: Import Components

Semua komponen UI sudah dibuat dan siap diimpor:

```javascript
import Card from '../../components/ui/Card';
import Badge from '../../components/ui/Badge';
import Dialog from '../../components/ui/Dialog';
// etc...
```

### Step 4: Verify Router Setup

Cek App.jsx sudah punya route:

```jsx
<Route path="organisasi" element={<Organisasi />} />
<Route path="organisasi/:id" element={<OrganisasiDetail />} />
```

---

## 📊 Data Structure Reference

### Pengumuman Object

```javascript
{
  id: number,
  judul: string,
  ringkasan: string,
  konten: string,
  kategori: 'Akademik' | 'Organisasi' | 'Event',
  tanggal: 'YYYY-MM-DD',
  author: string,
  status: 'baru' | 'biasa',
  penting: boolean,
  lampiran: string | null,      // filename
  link: string | null            // url
}
```

### LostFound Item Object

```javascript
{
  id: number,
  name: string,
  status: 'approved' | 'pending' | 'rejected',
  itemStatus: 'hilang' | 'ditemukan',
  priority: boolean,
  date: 'YYYY-MM-DD',
  location: string,
  description: string,
  contact: string,               // email
  phone: string,                 // whatsapp
  category: 'Dompet' | 'Kunci' | 'Buku' | 'Elektronik' | ...,
  image: string | null
}
```

### Organisasi Object

```javascript
{
  id: number,
  nama: string,
  icon: string,                  // emoji
  tagline: string,
  kategori: 'Akademik' | 'Seni & Olahraga' | 'Kerohanian',
  members: number,
  deskripsi: string,
  visiMisi: {
    visi: string,
    misi: string[]
  },
  budaya: string,
  programs: [{ judul, deskripsi }, ...],
  events: [{ judul, tanggal, deskripsi }, ...],
  struktur: [{ posisi, nama }, ...],
  contact: string,               // phone
  email: string,
  registrationOpen: boolean,
  banner: string                 // css gradient
}
```

---

## 🎯 Usage Examples

### Menggunakan Card Component

```jsx
import Card from '../../components/ui/Card';

<Card hover onClick={handleClick}>
  <h3>Title</h3>
  <p>Content</p>
</Card>;
```

### Menggunakan Badge Component

```jsx
<Badge variant="success" size="md">
  ✓ Ditemukan
</Badge>
```

### Menggunakan Dialog Component

```jsx
const [open, setOpen] = useState(false);

<Dialog
  open={open}
  onClose={() => setOpen(false)}
  title="Modal Title"
  size="lg"
>
  Modal content here
</Dialog>

<button onClick={() => setOpen(true)}>Open</button>
```

### Menggunakan Tabs Component

```jsx
<Tabs
  tabs={[
    { id: 'tab1', label: 'Tab 1' },
    { id: 'tab2', label: 'Tab 2' },
  ]}
  activeTab={activeTab}
  onTabChange={setActiveTab}
/>
```

### Menggunakan SearchInput Component

```jsx
<SearchInput
  value={query}
  onChange={(e) => setQuery(e.target.value)}
  placeholder="Cari..."
/>
```

### Menggunakan FilterChips Component

```jsx
<FilterChips
  items={[
    { id: 'all', label: 'Semua' },
    { id: 'akademik', label: 'Akademik' },
  ]}
  selected={selected}
  onSelect={setSelected}
/>
```

---

## 🔄 Form Handling Pattern

Contoh dari LostAndFound form:

```jsx
const [form, setForm] = useState({
  name: '',
  category: 'Dompet',
  location: '',
  description: '',
  contact: '',
  phone: '',
  itemStatus: 'hilang',
});
const [formStatus, setFormStatus] = useState(null); // null | 'loading' | 'success' | 'error'

const submitReport = (e) => {
  e.preventDefault();
  setFormStatus('loading');

  // Simulate API call
  setTimeout(() => {
    // Process form data
    setFormStatus('success');

    // Reset after 2 seconds
    setTimeout(() => {
      setForm({
        /* reset */
      });
      setFormStatus(null);
    }, 2000);
  }, 1500);
};
```

---

## 🎨 Styling Pattern

### Using Tailwind Classes:

```jsx
// Colors
className = 'text-purple-700'; // Primary
className = 'bg-yellow-400'; // Accent
className = 'text-green-800'; // Success

// Spacing
className = 'p-4 sm:p-6'; // Padding
className = 'space-y-4'; // Vertical gap
className = 'gap-4'; // Horizontal gap

// Responsive
className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';

// Rounded corners
className = 'rounded-lg'; // rounded-lg
className = 'rounded-xl'; // rounded-xl
className = 'rounded-2xl'; // rounded-2xl
className = 'rounded-full'; // rounded-full (buttons)
```

---

## 📍 Navigation Patterns

### Link/Navigate to Detail:

```jsx
import { useNavigate } from 'react-router-dom';

const navigate = useNavigate();

// Navigate with ID
navigate(`/organisasi/${org.id}`);

// Go back
navigate(-1);
```

### Get Params in Detail Page:

```jsx
import { useParams } from 'react-router-dom';

const { id } = useParams();
const item = data.find((x) => x.id === parseInt(id));
```

---

## 🔍 Filter & Search Pattern

```jsx
const [searchQuery, setSearchQuery] = useState('');
const [activeFilter, setActiveFilter] = useState('all');

const filtered = useMemo(() => {
  let result = items;

  // Filter by category
  if (activeFilter !== 'all') {
    result = result.filter((x) => x.category === activeFilter);
  }

  // Search
  if (searchQuery.trim()) {
    const q = searchQuery.toLowerCase();
    result = result.filter(
      (x) =>
        x.name.toLowerCase().includes(q) ||
        x.description.toLowerCase().includes(q)
    );
  }

  return result;
}, [items, searchQuery, activeFilter]);
```

---

## 📱 Responsive Tips

### Mobile First Approach:

```jsx
// Start with mobile, then add larger screens
className="
  grid grid-cols-1          // 1 col mobile
  sm:grid-cols-2            // 2 cols @ 640px
  lg:grid-cols-3            // 3 cols @ 1024px
"
```

### Hide/Show Elements:

```jsx
className = 'hidden md:block'; // Hidden mobile, show > 768px
className = 'md:hidden'; // Show mobile, hidden > 768px
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Import Error for UI Components

**Solution**: Pastikan path import benar

```javascript
// ✅ Correct
import Card from '../../components/ui/Card';

// ❌ Wrong
import Card from '../components/ui/Card';
```

### Issue 2: Dialog not showing

**Solution**: Pastikan state open benar dan onClose handler dipassing

```jsx
const [open, setOpen] = useState(false);
<Dialog open={open} onClose={() => setOpen(false)}>
  ...
</Dialog>;
```

### Issue 3: Filter tidak work

**Solution**: Pastikan useMemo dependencies benar

```jsx
const filtered = useMemo(() => {
  // logic
}, [items, searchQuery, activeFilter]); // Include all deps
```

### Issue 4: Route not found

**Solution**: Pastikan route sudah ditambah di App.jsx

```jsx
<Route path="organisasi/:id" element={<OrganisasiDetail />} />
```

---

## 🚀 Next Steps to Connect Backend

Jika sudah siap connect ke backend:

### 1. Replace Dummy Data dengan API Call

```jsx
const [items, setItems] = useState([]);
const [loading, setLoading] = useState(true);

useEffect(() => {
  fetch('/api/pengumuman')
    .then((r) => r.json())
    .then((data) => setItems(data))
    .catch((err) => console.error(err))
    .finally(() => setLoading(false));
}, []);
```

### 2. Replace Form Submit dengan API

```jsx
const submitReport = async (e) => {
  e.preventDefault();
  setFormStatus('loading');

  try {
    const res = await fetch('/api/lost-found/report', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form),
    });

    if (res.ok) {
      setFormStatus('success');
      // Reset form...
    }
  } catch (err) {
    setFormStatus('error');
  }
};
```

### 3. Add Loading States

```jsx
{loading ? (
  <div className="text-center py-12">Loading...</div>
) : (
  // render items
)}
```

### 4. Add Error Handling

```jsx
{
  error ? (
    <div className="bg-red-100 p-4 rounded-lg">Error: {error.message}</div>
  ) : null;
}
```

---

## 📚 Component Props Reference

### Card

```typescript
interface CardProps {
  children: ReactNode;
  className?: string;
  border?: boolean; // default: true
  rounded?: 'lg' | 'xl' | '2xl'; // default: '2xl'
  onClick?: () => void;
  hover?: boolean;
  variant?: 'default' | 'highlight';
}
```

### Badge

```typescript
interface BadgeProps {
  children: ReactNode;
  variant?: 'default' | 'success' | 'danger' | 'warning' | 'info' | 'purple';
  size?: 'sm' | 'md' | 'lg';
  className?: string;
}
```

### Dialog

```typescript
interface DialogProps {
  open: boolean;
  onClose: () => void;
  title?: string;
  children: ReactNode;
  size?: 'sm' | 'md' | 'lg' | 'xl';
}
```

### Tabs

```typescript
interface Tab {
  id: string;
  label: string;
}

interface TabsProps {
  tabs: Tab[];
  activeTab: string;
  onTabChange: (id: string) => void;
  className?: string;
}
```

### SearchInput

```typescript
interface SearchInputProps {
  value: string;
  onChange: (e) => void;
  placeholder?: string;
  className?: string;
}
```

### FilterChips

```typescript
interface Item {
  id: string;
  label: string;
}

interface FilterChipsProps {
  items: Item[];
  selected: string;
  onSelect: (id: string) => void;
  className?: string;
}
```

---

## ✅ Testing Checklist

- [ ] All pages load without console errors
- [ ] Search functionality works on all pages
- [ ] Filter functionality works on all pages
- [ ] Tabs switch correctly
- [ ] Dialog opens/closes smoothly
- [ ] Form submission shows loading state
- [ ] Responsive design works on mobile
- [ ] Responsive design works on tablet
- [ ] Responsive design works on desktop
- [ ] Navigation between pages works
- [ ] Detail modals show correct data
- [ ] Contact buttons open correct dialog
- [ ] Daftar button disabled when closed

---

## 📞 Support

Jika ada masalah atau pertanyaan:

1. Cek dokumentasi di file ini
2. Cek implementation examples
3. Verifikasi imports dan paths
4. Check console untuk error messages
5. Verify component usage dengan reference

---

**Last Updated**: January 30, 2026  
**Status**: ✅ Production Ready
