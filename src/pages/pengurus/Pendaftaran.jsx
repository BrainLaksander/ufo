import React, { useState } from 'react';
import { FileText, Plus, Eye, Check, X, Search, Filter } from 'lucide-react';

/**
 * Pendaftaran Anggota Page - Pengurus
 *
 * Halaman untuk mengelola pendaftaran anggota baru:
 * - View daftar pendaftar
 * - Terima atau tolak pendaftaran
 * - View detail lengkap pendaftar
 */
export default function Pendaftaran() {
  const [registrations, setRegistrations] = useState([
    {
      id: 1,
      name: 'Ahmad Rifki',
      nim: '2024001',
      faculty: 'Teknik Informatika',
      year: '2024',
      reason:
        'Ingin bergabung dengan komunitas teknologi dan mengembangkan skill programming.',
      status: 'pending',
      appliedDate: '2026-03-01',
      email: 'ahmad.rifki@email.com',
      phone: '081234567890',
    },
    {
      id: 2,
      name: 'Siti Nurhaliza',
      nim: '2024002',
      faculty: 'Bisnis',
      year: '2024',
      reason:
        'Tertarik menambah pengalaman organisasi dan jaringan profesional.',
      status: 'pending',
      appliedDate: '2026-02-28',
      email: 'siti.nur@email.com',
      phone: '082345678901',
    },
    {
      id: 3,
      name: 'Budi Santoso',
      nim: '2024003',
      faculty: 'Hukum',
      year: '2024',
      reason: 'Ingin berkontribusi untuk organisasi mahasiswa.',
      status: 'approved',
      appliedDate: '2026-02-27',
      email: 'budi.santo@email.com',
      phone: '083456789012',
    },
    {
      id: 4,
      name: 'Maya Putri',
      nim: '2024004',
      faculty: 'Teknik Elektro',
      year: '2024',
      reason: 'Penasaran dengan visi misi organisasi ini.',
      status: 'rejected',
      appliedDate: '2026-02-26',
      email: 'maya.putri@email.com',
      phone: '084567890123',
    },
  ]);

  const [showDetail, setShowDetail] = useState(false);
  const [selectedRegistration, setSelectedRegistration] = useState(null);
  const [statusFilter, setStatusFilter] = useState('pending');
  const [searchQuery, setSearchQuery] = useState('');

  const filteredRegistrations = registrations.filter((reg) => {
    let match = true;
    if (statusFilter !== 'all' && reg.status !== statusFilter) match = false;
    if (
      searchQuery &&
      !reg.name.toLowerCase().includes(searchQuery.toLowerCase()) &&
      !reg.nim.includes(searchQuery)
    ) {
      match = false;
    }
    return match;
  });

  const viewDetail = (registration) => {
    setSelectedRegistration(registration);
    setShowDetail(true);
  };

  const handleApprove = (id) => {
    if (confirm('Terima pendaftaran anggota ini?')) {
      setRegistrations(
        registrations.map((r) =>
          r.id === id ? { ...r, status: 'approved' } : r
        )
      );
      if (selectedRegistration && selectedRegistration.id === id) {
        setSelectedRegistration({
          ...selectedRegistration,
          status: 'approved',
        });
      }
    }
  };

  const handleReject = (id) => {
    if (confirm('Tolak pendaftaran anggota ini?')) {
      setRegistrations(
        registrations.map((r) =>
          r.id === id ? { ...r, status: 'rejected' } : r
        )
      );
      if (selectedRegistration && selectedRegistration.id === id) {
        setSelectedRegistration({
          ...selectedRegistration,
          status: 'rejected',
        });
      }
    }
  };

  const StatCard = ({ icon: Icon, label, value, color }) => (
    <div className="bg-white p-4 rounded-xl border-2 border-gray-200">
      <div className="flex items-center gap-3">
        <div className={`p-3 rounded-lg ${color}`}>
          <Icon size={24} />
        </div>
        <div>
          <p className="text-xs text-gray-500 uppercase">{label}</p>
          <p className="text-2xl font-bold text-gray-900">{value}</p>
        </div>
      </div>
    </div>
  );

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">
          Pendaftaran Anggota Baru
        </h1>
        <p className="text-gray-600 mt-1">
          Kelola pendaftaran mahasiswa yang ingin bergabung
        </p>
      </div>

      {/* Statistics */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <StatCard
          icon={FileText}
          label="Total Pendaftar"
          value={registrations.length}
          color="bg-blue-100 text-blue-700"
        />
        <StatCard
          icon={FileText}
          label="Menunggu"
          value={registrations.filter((r) => r.status === 'pending').length}
          color="bg-yellow-100 text-yellow-700"
        />
        <StatCard
          icon={FileText}
          label="Diterima"
          value={registrations.filter((r) => r.status === 'approved').length}
          color="bg-green-100 text-green-700"
        />
        <StatCard
          icon={FileText}
          label="Ditolak"
          value={registrations.filter((r) => r.status === 'rejected').length}
          color="bg-red-100 text-red-700"
        />
      </div>

      {/* Search & Filter */}
      <div className="bg-white p-6 rounded-2xl border-2 border-gray-200">
        <div className="space-y-4">
          {/* Search */}
          <div className="relative">
            <Search
              className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"
              size={20}
            />
            <input
              type="text"
              placeholder="Cari nama atau NIM..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
            />
          </div>

          {/* Status Filter */}
          <div className="flex gap-2 flex-wrap">
            <Filter size={18} className="text-gray-600 mt-2" />
            {['pending', 'approved', 'rejected', 'all'].map((status) => (
              <button
                key={status}
                onClick={() => setStatusFilter(status)}
                className={`px-4 py-2 rounded-lg font-semibold text-sm border-2 transition-colors ${
                  statusFilter === status
                    ? 'bg-blue-100 text-blue-700 border-blue-300'
                    : 'bg-gray-100 text-gray-700 border-gray-300 hover:border-blue-300'
                }`}
              >
                {status === 'pending'
                  ? 'Menunggu'
                  : status === 'approved'
                    ? 'Diterima'
                    : status === 'rejected'
                      ? 'Ditolak'
                      : 'Semua'}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Registrations List */}
      <div className="space-y-4">
        {filteredRegistrations.length === 0 ? (
          <div className="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
            <FileText className="mx-auto mb-3 text-gray-400" size={48} />
            <p className="text-gray-600 font-semibold">Tidak ada pendaftaran</p>
          </div>
        ) : (
          filteredRegistrations.map((reg) => (
            <div
              key={reg.id}
              className="bg-white p-6 rounded-2xl border-2 border-gray-200 hover:shadow-lg transition-shadow"
            >
              <div className="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                  <h3 className="text-lg font-bold text-gray-900">
                    {reg.name}
                  </h3>
                  <p className="text-sm text-gray-600">{reg.nim}</p>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Fakultas</p>
                  <p className="font-semibold text-gray-900">{reg.faculty}</p>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Angkatan</p>
                  <p className="font-semibold text-gray-900">{reg.year}</p>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Status</p>
                  <span
                    className={`px-3 py-1 rounded-full text-sm font-bold border-2 inline-block mt-1 ${
                      reg.status === 'pending'
                        ? 'bg-yellow-100 text-yellow-700 border-yellow-300'
                        : reg.status === 'approved'
                          ? 'bg-green-100 text-green-700 border-green-300'
                          : 'bg-red-100 text-red-700 border-red-300'
                    }`}
                  >
                    {reg.status === 'pending'
                      ? 'Menunggu'
                      : reg.status === 'approved'
                        ? 'Diterima'
                        : 'Ditolak'}
                  </span>
                </div>

                <div className="flex gap-2 flex-wrap">
                  <button
                    onClick={() => viewDetail(reg)}
                    className="flex items-center gap-2 px-3 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors font-semibold border-2 border-blue-300 text-sm"
                  >
                    <Eye size={14} />
                    Detail
                  </button>
                </div>
              </div>

              <div className="border-t-2 border-gray-200 pt-3 flex items-center justify-between">
                <p className="text-xs text-gray-500">
                  Mendaftar: {reg.appliedDate}
                </p>

                {reg.status === 'pending' && (
                  <div className="flex gap-2">
                    <button
                      onClick={() => handleApprove(reg.id)}
                      className="flex items-center gap-2 px-4 py-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors font-semibold border-2 border-green-300 text-sm"
                    >
                      <Check size={14} />
                      Terima
                    </button>
                    <button
                      onClick={() => handleReject(reg.id)}
                      className="flex items-center gap-2 px-4 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors font-semibold border-2 border-red-300 text-sm"
                    >
                      <X size={14} />
                      Tolak
                    </button>
                  </div>
                )}
              </div>
            </div>
          ))
        )}
      </div>

      {/* Detail Modal */}
      {showDetail && selectedRegistration && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-2xl w-full shadow-2xl">
            <div className="border-b-2 border-gray-200 p-6 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-gray-900">
                {selectedRegistration.name}
              </h2>
              <button
                onClick={() => setShowDetail(false)}
                className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <X size={24} />
              </button>
            </div>

            <div className="p-6 space-y-6">
              <div className="bg-gray-50 p-6 rounded-xl border-2 border-gray-200">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                  <div>
                    <p className="text-xs text-gray-500 uppercase">NIM</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedRegistration.nim}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Fakultas</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedRegistration.faculty}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Angkatan</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedRegistration.year}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Email</p>
                    <p className="font-semibold text-gray-900 mt-1 break-all">
                      {selectedRegistration.email}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">No. Telp</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedRegistration.phone}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">
                      Tanggal Daftar
                    </p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedRegistration.appliedDate}
                    </p>
                  </div>
                </div>

                <div className="border-t-2 border-gray-300 pt-4">
                  <p className="text-xs text-gray-500 uppercase mb-2">
                    Alasan Bergabung
                  </p>
                  <p className="text-gray-900">{selectedRegistration.reason}</p>
                </div>
              </div>

              {/* Status Badge */}
              <div>
                <p className="text-sm font-bold text-gray-900 mb-2">Status</p>
                <span
                  className={`px-4 py-2 rounded-lg text-sm font-bold border-2 inline-block ${
                    selectedRegistration.status === 'pending'
                      ? 'bg-yellow-100 text-yellow-700 border-yellow-300'
                      : selectedRegistration.status === 'approved'
                        ? 'bg-green-100 text-green-700 border-green-300'
                        : 'bg-red-100 text-red-700 border-red-300'
                  }`}
                >
                  {selectedRegistration.status === 'pending'
                    ? 'Menunggu'
                    : selectedRegistration.status === 'approved'
                      ? 'Diterima'
                      : 'Ditolak'}
                </span>
              </div>

              {/* Actions */}
              <div className="flex gap-3 pt-4 border-t-2 border-gray-200 flex-wrap">
                {selectedRegistration.status === 'pending' && (
                  <>
                    <button
                      onClick={() => {
                        handleApprove(selectedRegistration.id);
                        setShowDetail(false);
                      }}
                      className="flex-1 min-w-[150px] px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold border-2 border-green-700"
                    >
                      Terima Pendaftaran
                    </button>
                    <button
                      onClick={() => {
                        handleReject(selectedRegistration.id);
                        setShowDetail(false);
                      }}
                      className="flex-1 min-w-[150px] px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold border-2 border-red-700"
                    >
                      Tolak Pendaftaran
                    </button>
                  </>
                )}
                <button
                  onClick={() => setShowDetail(false)}
                  className="flex-1 min-w-[150px] px-6 py-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold border-2 border-gray-300"
                >
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
