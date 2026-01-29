import React, { Suspense, lazy } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './auth/AuthProvider';
import ProtectedRoute from './auth/ProtectedRoute';
import { NotificationProvider } from './contexts/NotificationProvider';
import MahasiswaLayout from './layouts/MahasiswaLayout';
import KemahasiswaanLayout from './layouts/KemahasiswaanLayout';
import PengurusLayout from './layouts/PengurusLayout';
import AdminLayout from './layouts/AdminLayout';
import Spinner from './components/ui/Spinner';

// Lazy-loaded pages (keuntungan: split chunks)
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
        <BrowserRouter basename="/portal">
          <Suspense fallback={<Spinner />}>
            <Routes>
              {/* Public routes - Mahasiswa (no login required) */}
              <Route element={<MahasiswaLayout />}>
                <Route index element={<Home />} />
                <Route path="organisasi" element={<Organisasi />} />
                <Route path="event" element={<EventPage />} />
                <Route path="pengumuman" element={<Pengumuman />} />
                {/* ...more public pages */}
              </Route>

              {/* Auth routes */}
              <Route path="login" element={<Login />} />
              {/* Pengurus login (public) */}
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
                {/* nested kemahasiswaan pages go here (manajemen organisasi, pendaftaran, etc.) */}
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
                {/* other pengurus pages can be added here (placeholders) */}
              </Route>

              {/* Protected: Admin (placeholder) */}
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

              {/* fallback */}
              <Route path="unauthorized" element={<div>Unauthorized</div>} />
              <Route path="*" element={<Navigate to="/" replace />} />
            </Routes>
          </Suspense>
        </BrowserRouter>
      </NotificationProvider>
    </AuthProvider>
  );
}
