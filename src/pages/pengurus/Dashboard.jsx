import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import {
  Users,
  Calendar,
  Bell,
  Package,
  Plus,
  TrendingUp,
  Clock,
  CheckCircle,
} from 'lucide-react';

/**
 * Dashboard Pengurus
 *
 * Halaman utama dashboard pengurus organisasi dengan:
 * - Statistik ringkas
 * - Event terbaru
 * - Pendaftaran terbaru
 * - Quick action buttons
 */
export default function Dashboard() {
  const navigate = useNavigate();

  // Dummy data statistics
  const [stats] = useState({
    totalMembers: 142,
    activeEvents: 5,
    activeAnnouncements: 8,
    lostFoundReports: 23,
  });

  // Dummy data upcomingEvents
  const [upcomingEvents] = useState([
    {
      id: 1,
      name: 'Rapat Organisasi Bulanan',
      date: '2026-03-05',
      time: '15:00',
      status: 'open',
      participants: 87,
      quota: 150,
    },
    {
      id: 2,
      name: 'Diskusi Publik: Tech & Career',
      date: '2026-03-12',
      time: '14:00',
      status: 'open',
      participants: 156,
      quota: 200,
    },
    {
      id: 3,
      name: 'Workshop Python untuk Pemula',
      date: '2026-03-18',
      time: '13:00',
      status: 'draft',
      participants: 0,
      quota: 50,
    },
  ]);

  // Dummy data recent registrations
  const [recentRegistrations] = useState([
    {
      id: 1,
      name: 'Ahmad Rifki',
      nim: '2024001',
      faculty: 'Teknik Informatika',
      status: 'pending',
      appliedDate: '2026-03-01',
    },
    {
      id: 2,
      name: 'Siti Nurhaliza',
      nim: '2024002',
      faculty: 'Bisnis',
      status: 'pending',
      appliedDate: '2026-02-28',
    },
    {
      id: 3,
      name: 'Budi Santoso',
      nim: '2024003',
      faculty: 'Hukum',
      status: 'approved',
      appliedDate: '2026-02-27',
    },
  ]);

  // Dummy data recent lost & found
  const [recentLostFound] = useState([
    {
      id: 1,
      item: 'Kaci Kucing Hitam',
      type: 'lost',
      location: 'Area Kampus Utama',
      status: 'open',
      reporter: 'Mahasiswa Anonim',
      date: '2026-03-01',
    },
    {
      id: 2,
      item: 'Dompet Merah Kulit',
      type: 'found',
      location: 'Kantin Area B',
      status: 'open',
      reporter: 'Security Kampus',
      date: '2026-02-28',
    },
    {
      id: 3,
      item: 'Tas Ransel Biru',
      type: 'lost',
      location: 'Perpustakaan',
      status: 'resolved',
      reporter: 'Mahasiswa Anonim',
      date: '2026-02-25',
    },
  ]);

  // Quick actions
  const quickActions = [
    {
      icon: Calendar,
      label: 'Buat Event',
      color: 'bg-blue-100 text-blue-700',
      action: () => navigate('/pengurus/event'),
    },
    {
      icon: Bell,
      label: 'Buat Pengumuman',
      color: 'bg-green-100 text-green-700',
      action: () => navigate('/pengurus/pengumuman'),
    },
    {
      icon: Package,
      label: 'Laporan Lost & Found',
      color: 'bg-yellow-100 text-yellow-700',
      action: () => navigate('/pengurus/lost-found'),
    },
    {
      icon: Users,
      label: 'Kelola Anggota',
      color: 'bg-purple-100 text-purple-700',
      action: () => navigate('/pengurus/anggota'),
    },
  ];

  const StatCard = ({ icon: Icon, label, value, color }) => (
    <div className="bg-white p-6 rounded-2xl border-2 border-gray-200 shadow-lg hover:shadow-xl transition-shadow">
      <div className="flex items-center justify-between">
        <div>
          <p className="text-gray-600 text-sm font-medium">{label}</p>
          <p className="text-3xl font-bold text-gray-900 mt-2">{value}</p>
        </div>
        <div className={`p-4 rounded-xl ${color}`}>
          <Icon size={28} />
        </div>
      </div>
    </div>
  );

  return (
    <div className="space-y-8">
      {/* Page Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">Dashboard Pengurus</h1>
        <p className="text-gray-600 mt-2">
          Selamat datang! Kelola organisasi Anda dari sini.
        </p>
      </div>

      {/* Statistics Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard
          icon={Users}
          label="Total Anggota"
          value={stats.totalMembers}
          color="bg-blue-100 text-blue-700"
        />
        <StatCard
          icon={Calendar}
          label="Event Aktif"
          value={stats.activeEvents}
          color="bg-green-100 text-green-700"
        />
        <StatCard
          icon={Bell}
          label="Pengumuman Aktif"
          value={stats.activeAnnouncements}
          color="bg-yellow-100 text-yellow-700"
        />
        <StatCard
          icon={Package}
          label="Laporan L&F"
          value={stats.lostFoundReports}
          color="bg-purple-100 text-purple-700"
        />
      </div>

      {/* Quick Actions */}
      <div className="bg-gradient-to-r from-purple-50 to-blue-50 p-8 rounded-2xl border-2 border-purple-200">
        <h2 className="text-xl font-bold text-gray-900 mb-6">⚡ Aksi Cepat</h2>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {quickActions.map((action, idx) => {
            const Icon = action.icon;
            return (
              <button
                key={idx}
                onClick={action.action}
                className={`flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-transparent hover:border-purple-400 transition-all hover:scale-105 active:scale-95 ${action.color}`}
              >
                <Icon size={28} />
                <span className="text-sm font-semibold text-center">
                  {action.label}
                </span>
              </button>
            );
          })}
        </div>
      </div>

      {/* Main Content Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Upcoming Events */}
        <div className="lg:col-span-2">
          <div className="bg-white rounded-2xl border-2 border-gray-200 shadow-lg p-6">
            <div className="flex items-center justify-between mb-6">
              <h2 className="text-xl font-bold text-gray-900 flex items-center gap-2">
                <Calendar className="text-blue-600" size={24} />
                Event Mendatang
              </h2>
              <button
                onClick={() => navigate('/pengurus/event')}
                className="text-blue-600 hover:text-blue-700 font-semibold text-sm"
              >
                Lihat Semua →
              </button>
            </div>

            <div className="space-y-4">
              {upcomingEvents.slice(0, 3).map((event) => (
                <div
                  key={event.id}
                  className="p-4 bg-gray-50 rounded-xl border-2 border-gray-200 hover:bg-gray-100 transition-colors cursor-pointer"
                >
                  <div className="flex items-start justify-between mb-2">
                    <h3 className="font-semibold text-gray-900">
                      {event.name}
                    </h3>
                    <span
                      className={`px-3 py-1 rounded-full text-xs font-bold border-2 ${
                        event.status === 'open'
                          ? 'bg-green-100 text-green-700 border-green-300'
                          : 'bg-yellow-100 text-yellow-700 border-yellow-300'
                      }`}
                    >
                      {event.status === 'open' ? 'Dibuka' : 'Draft'}
                    </span>
                  </div>
                  <p className="text-sm text-gray-600 mb-2">
                    📅 {event.date} • 🕐 {event.time}
                  </p>
                  <div className="w-full bg-gray-200 rounded-full h-2">
                    <div
                      className="bg-blue-500 h-2 rounded-full"
                      style={{
                        width: `${(event.participants / event.quota) * 100}%`,
                      }}
                    />
                  </div>
                  <p className="text-xs text-gray-500 mt-1">
                    {event.participants} / {event.quota} peserta
                  </p>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Recent Registrations */}
        <div className="bg-white rounded-2xl border-2 border-gray-200 shadow-lg p-6">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-lg font-bold text-gray-900 flex items-center gap-2">
              <Users className="text-green-600" size={24} />
              Pendaftaran Baru
            </h2>
          </div>

          <div className="space-y-3">
            {recentRegistrations.slice(0, 3).map((reg) => (
              <div
                key={reg.id}
                className="p-3 bg-gray-50 rounded-lg border-2 border-gray-200 hover:bg-gray-100 transition-colors"
              >
                <div className="flex items-start justify-between mb-1">
                  <p className="font-semibold text-sm text-gray-900">
                    {reg.name}
                  </p>
                  <span
                    className={`px-2 py-1 rounded text-xs font-bold border ${
                      reg.status === 'pending'
                        ? 'bg-yellow-100 text-yellow-700 border-yellow-300'
                        : 'bg-green-100 text-green-700 border-green-300'
                    }`}
                  >
                    {reg.status === 'pending' ? 'Pending' : 'Diterima'}
                  </span>
                </div>
                <p className="text-xs text-gray-600">{reg.nim}</p>
                <p className="text-xs text-gray-500">{reg.faculty}</p>
              </div>
            ))}
          </div>

          <button
            onClick={() => navigate('/pengurus/pendaftaran')}
            className="w-full mt-4 py-2 bg-green-100 text-green-700 font-semibold rounded-lg hover:bg-green-200 transition-colors text-sm border-2 border-green-300"
          >
            Lihat Semua Pendaftaran
          </button>
        </div>
      </div>

      {/* Lost & Found Updates */}
      <div className="bg-white rounded-2xl border-2 border-gray-200 shadow-lg p-6">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-xl font-bold text-gray-900 flex items-center gap-2">
            <Package className="text-purple-600" size={24} />
            Lost & Found Terbaru
          </h2>
          <button
            onClick={() => navigate('/pengurus/lost-found')}
            className="text-purple-600 hover:text-purple-700 font-semibold text-sm"
          >
            Lihat Semua →
          </button>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          {recentLostFound.map((item) => (
            <div
              key={item.id}
              className="p-4 bg-gray-50 rounded-xl border-2 border-gray-200 hover:shadow-md transition-shadow"
            >
              <div className="flex items-start justify-between mb-3">
                <h3 className="font-semibold text-gray-900 text-sm">
                  {item.item}
                </h3>
                <span
                  className={`px-2 py-1 rounded text-xs font-bold border-2 ${
                    item.type === 'lost'
                      ? 'bg-red-100 text-red-700 border-red-300'
                      : 'bg-blue-100 text-blue-700 border-blue-300'
                  }`}
                >
                  {item.type === 'lost' ? 'Hilang' : 'Ditemukan'}
                </span>
              </div>
              <p className="text-xs text-gray-600 mb-2">📍 {item.location}</p>
              <p className="text-xs text-gray-500 mb-3">{item.date}</p>
              <div className="flex gap-2">
                <span
                  className={`px-2 py-1 rounded text-xs font-bold border-2 flex-grow text-center ${
                    item.status === 'resolved'
                      ? 'bg-green-100 text-green-700 border-green-300'
                      : 'bg-yellow-100 text-yellow-700 border-yellow-300'
                  }`}
                >
                  {item.status === 'resolved' ? 'Selesai' : 'Terbuka'}
                </span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
