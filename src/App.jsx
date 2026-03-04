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
const OrganisasiDetail = lazy(() => import('./pages/public/OrganisasiDetail'));
const EventPage = lazy(() => import('./pages/public/Event'));
const Pengumuman = lazy(() => import('./pages/public/Pengumuman'));
const LostAndFound = lazy(() => import('./pages/public/LostAndFound'));
const TentangUFO = lazy(() => import('./pages/public/TentangUFO'));
const Login = lazy(() => import('./pages/auth/Login'));

const KemahasiswaanDashboard = lazy(
  () => import('./pages/kemahasiswaan/Dashboard')
);

const PengurusDashboard = lazy(() => import('./pages/pengurus/Dashboard'));
const PengurusLogin = lazy(() => import('./pages/pengurus/Login'));
const PengurusProfil = lazy(() => import('./pages/pengurus/ProfilOrganisasi'));
const PengurusEvent = lazy(() => import('./pages/pengurus/Event'));
const PengurusPengumuman = lazy(() => import('./pages/pengurus/Pengumuman'));
const PengurusLostFound = lazy(() => import('./pages/pengurus/LostFound'));
const PengurusAnggota = lazy(() => import('./pages/pengurus/Anggota'));
const PengurusPendaftaran = lazy(() => import('./pages/pengurus/Pendaftaran'));
const PengurusProposal = lazy(() => import('./pages/pengurus/Proposal'));
const PengurusPengajuan = lazy(
  () => import('./pages/pengurus/PengajuanLaporan')
);
const PengurusPengaturan = lazy(() => import('./pages/pengurus/Pengaturan'));
const AdminDashboard = lazy(() => import('./pages/admin/Dashboard'));

export default function App() {
  return (
    <AuthProvider>
      <NotificationProvider>
        <BrowserRouter>
          <Suspense fallback={<Spinner />}>
            <Routes>
              {/* Public routes - Mahasiswa (no login required) */}
              <Route element={<MahasiswaLayout />}>
                <Route index element={<Home />} />
                <Route path="organisasi" element={<Organisasi />} />
                <Route path="organisasi/:id" element={<OrganisasiDetail />} />
                <Route path="event" element={<EventPage />} />
                <Route path="pengumuman" element={<Pengumuman />} />
                <Route path="lost-found" element={<LostAndFound />} />
                <Route path="tentang-ufo" element={<TentangUFO />} />
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
                <Route path="event" element={<PengurusEvent />} />
                <Route path="pengumuman" element={<PengurusPengumuman />} />
                <Route path="lost-found" element={<PengurusLostFound />} />
                <Route path="anggota" element={<PengurusAnggota />} />
                <Route path="pendaftaran" element={<PengurusPendaftaran />} />
                <Route path="proposal" element={<PengurusProposal />} />
                <Route path="pengaturan" element={<PengurusPengaturan />} />
                <Route path="profil" element={<PengurusProfil />} />
                <Route
                  path="pengajuan-laporan"
                  element={<PengurusPengajuan />}
                />
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
