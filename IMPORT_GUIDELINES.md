# 📌 CONTOH IMPORT YANG BENAR

## ✅ File: src/App.jsx

```javascript
import React, { Suspense, lazy } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './auth/AuthProvider';
import ProtectedRoute from './auth/ProtectedRoute';
import { NotificationProvider } from './contexts/NotificationProvider';

// Layouts
import MahasiswaLayout from './layouts/MahasiswaLayout';
import KemahasiswaanLayout from './layouts/KemahasiswaanLayout';
import PengurusLayout from './layouts/PengurusLayout';
import AdminLayout from './layouts/AdminLayout';

// UI Components
import Spinner from './components/ui/Spinner';

// Lazy-loaded pages
const Home = lazy(() => import('./pages/public/Home'));
const Organisasi = lazy(() => import('./pages/public/Organisasi'));
const EventPage = lazy(() => import('./pages/public/Event'));
const Pengumuman = lazy(() => import('./pages/public/Pengumuman'));
const Login = lazy(() => import('./pages/auth/Login'));

const KemahasiswaanDashboard = lazy(
  () => import('./pages/kemahasiswaan/Dashboard')
);
const PengurusDashboard = lazy(() => import('./pages/pengurus/Dashboard'));
const PengurusLogin = lazy(() => import('./pages/pengurus/Login'));
const PengurusProfil = lazy(() => import('./pages/pengurus/ProfilOrganisasi'));
const AdminDashboard = lazy(() => import('./pages/admin/Dashboard'));

export default function App() {
  return (
    <AuthProvider>
      <NotificationProvider>
        <BrowserRouter>
          <Suspense fallback={<Spinner />}>
            <Routes>
              {/* Public routes - Mahasiswa */}
              <Route element={<MahasiswaLayout />}>
                <Route index element={<Home />} />
                <Route path="organisasi" element={<Organisasi />} />
                <Route path="event" element={<EventPage />} />
                <Route path="pengumuman" element={<Pengumuman />} />
              </Route>

              {/* Auth routes */}
              <Route path="login" element={<Login />} />
              <Route path="pengurus/login" element={<PengurusLogin />} />

              {/* Protected: Kemahasiswaan */}
              <Route
                path="kemahasiswaan/*"
                element={
                  <ProtectedRoute allowedRoles={['kemahasiswaan']}>
                    <KemahasiswaanLayout />
                  </ProtectedRoute>
                }
              >
                <Route index element={<KemahasiswaanDashboard />} />
              </Route>

              {/* Protected: Pengurus */}
              <Route
                path="pengurus/*"
                element={
                  <ProtectedRoute allowedRoles={['pengurus']}>
                    <PengurusLayout />
                  </ProtectedRoute>
                }
              >
                <Route index element={<PengurusDashboard />} />
                <Route path="profil" element={<PengurusProfil />} />
              </Route>

              {/* Protected: Admin */}
              <Route
                path="admin/*"
                element={
                  <ProtectedRoute allowedRoles={['admin']}>
                    <AdminLayout />
                  </ProtectedRoute>
                }
              >
                <Route index element={<AdminDashboard />} />
              </Route>

              {/* Fallback */}
              <Route path="unauthorized" element={<div>Unauthorized</div>} />
              <Route path="*" element={<Navigate to="/" replace />} />
            </Routes>
          </Suspense>
        </BrowserRouter>
      </NotificationProvider>
    </AuthProvider>
  );
}
```

---

## ✅ File: src/pages/pengurus/Dashboard.jsx

```javascript
import React from 'react';

export default function Dashboard() {
  return (
    <div>
      <h2 className="text-2xl font-semibold">Dashboard Pengurus</h2>
      <p className="mt-4 text-gray-600">
        Ini adalah placeholder dashboard untuk pengurus.
      </p>
    </div>
  );
}
```

---

## ✅ File: src/pages/pengurus/Login.jsx

```javascript
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  function handleSubmit(e) {
    e.preventDefault();
    navigate('/pengurus');
  }

  return (
    <div className="max-w-md mx-auto bg-white p-6 rounded shadow">
      <h2 className="text-xl font-semibold mb-4">Login Pengurus</h2>
      <form onSubmit={handleSubmit} className="space-y-3">
        <div>
          <label className="block text-sm">Email</label>
          <input
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="mt-1 block w-full border px-3 py-2 rounded"
            placeholder="pengurus@contoh.test"
          />
        </div>
        <div>
          <label className="block text-sm">Password</label>
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="mt-1 block w-full border px-3 py-2 rounded"
            placeholder="••••••••"
          />
        </div>
        <div className="flex justify-end">
          <button className="px-4 py-2 bg-blue-600 text-white rounded">
            Login
          </button>
        </div>
      </form>
    </div>
  );
}
```

---

## ✅ File: src/pages/pengurus/ProfilOrganisasi.jsx

```javascript
import React from 'react';

export default function ProfilOrganisasi() {
  const org = {
    nama: 'Himpunan Mahasiswa Contoh',
    ketua: 'Budi',
    kontak: 'hmc@example.test',
    deskripsi: 'Organisasi mahasiswa yang aktif dalam kegiatan kampus.',
  };

  return (
    <div>
      <h2 className="text-2xl font-semibold">Profil Organisasi</h2>
      <div className="mt-4 bg-white p-4 rounded shadow">
        <p className="font-semibold">
          Nama: <span className="font-normal">{org.nama}</span>
        </p>
        <p className="mt-2">Ketua: {org.ketua}</p>
        <p className="mt-2">Kontak: {org.kontak}</p>
        <p className="mt-3 text-gray-700">{org.deskripsi}</p>
      </div>
    </div>
  );
}
```

---

## 📋 IMPORT RULES - BEST PRACTICE

### ✅ DO:

- Gunakan **single quotes** (`'`) untuk strings
- Pisahkan imports dalam **3 grup**: React → Libraries → Project
- Lazy load components yang berat
- Alphabetical order dalam group yang sama
- 2-space indentation

### ❌ DON'T:

- Jangan campur single & double quotes
- Jangan import semuanya secara default
- Jangan nested imports yang mendalam
- Jangan relative imports yang panjang (gunakan path alias jika perlu)
- Jangan import yang tidak digunakan

---

## 🔧 NPM SCRIPTS (package.json)

```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "preview": "vite preview",
    "format": "prettier --write \"src/**/*.{js,jsx}\"",
    "format:check": "prettier --check \"src/**/*.{js,jsx}\"",
    "lint": "eslint src/",
    "lint:fix": "eslint src/ --fix"
  }
}
```

---

## 🚀 COMMAND UNTUK CLEANUP

```bash
# Format all files
npm run format

# Check linting
npm run lint

# Fix linting issues
npm run lint:fix

# Check formatting
npm run format:check
```
