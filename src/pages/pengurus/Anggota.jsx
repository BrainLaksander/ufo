import React, { useState } from 'react';
import { Users, Edit2, Trash2, Eye, Search, Filter, X } from 'lucide-react';

/**
 * Member Management Page - Pengurus
 *
 * Halaman untuk mengelola anggota organisasi:
 * - View daftar anggota aktif
 * - Ubah jabatan
 * - Nonaktifkan anggota
 * - Search & filter
 */
export default function Anggota() {
  const [members, setMembers] = useState([
    {
      id: 1,
      name: 'Ahmad Rifki',
      nim: '2023001',
      faculty: 'Teknik Informatika',
      position: 'Ketua',
      status: 'active',
      joinDate: '2023-09-15',
      email: 'ahmad.rifki@student.univ.id',
      phone: '081234567890',
    },
    {
      id: 2,
      name: 'Siti Nurhaliza',
      nim: '2023002',
      faculty: 'Bisnis',
      position: 'Sekretaris',
      status: 'active',
      joinDate: '2023-09-20',
      email: 'siti.nuh@student.univ.id',
      phone: '082345678901',
    },
    {
      id: 3,
      name: 'Budi Santoso',
      nim: '2023003',
      faculty: 'Hukum',
      position: 'Bendahara',
      status: 'active',
      joinDate: '2023-10-01',
      email: 'budi.santo@student.univ.id',
      phone: '083456789012',
    },
    {
      id: 4,
      name: 'Maya Putri',
      nim: '2024001',
      faculty: 'Teknik Elektro',
      position: 'Staff',
      status: 'active',
      joinDate: '2024-02-10',
      email: 'maya.putri@student.univ.id',
      phone: '084567890123',
    },
    {
      id: 5,
      name: 'Rinto Wijaya',
      nim: '2023004',
      faculty: 'Teknik Informatika',
      position: 'Staff',
      status: 'inactive',
      joinDate: '2023-11-05',
      email: 'rinto.wij@student.univ.id',
      phone: '085678901234',
    },
  ]);

  const [showDetail, setShowDetail] = useState(false);
  const [selectedMember, setSelectedMember] = useState(null);
  const [editingMember, setEditingMember] = useState(null);
  const [statusFilter, setStatusFilter] = useState('active');
  const [searchQuery, setSearchQuery] = useState('');
  const [showEditForm, setShowEditForm] = useState(false);
  const [newPosition, setNewPosition] = useState('');

  const positions = [
    'Ketua',
    'Sekretaris',
    'Bendahara',
    'Staff',
    'Anggota Biasa',
  ];

  const filteredMembers = members.filter((member) => {
    let match = true;
    if (statusFilter !== 'all' && member.status !== statusFilter) match = false;
    if (
      searchQuery &&
      !member.name.toLowerCase().includes(searchQuery.toLowerCase()) &&
      !member.nim.includes(searchQuery)
    ) {
      match = false;
    }
    return match;
  });

  const viewDetail = (member) => {
    setSelectedMember(member);
    setShowDetail(true);
  };

  const openEditForm = (member) => {
    setEditingMember(member);
    setNewPosition(member.position);
    setShowEditForm(true);
  };

  const savePositionChange = () => {
    if (!newPosition) {
      alert('Pilih jabatan baru');
      return;
    }

    setMembers(
      members.map((m) =>
        m.id === editingMember.id ? { ...m, position: newPosition } : m
      )
    );
    setShowEditForm(false);
    alert('Jabatan berhasil diubah menjadi ' + newPosition);
  };

  const toggleStatus = (memberId) => {
    const member = members.find((m) => m.id === memberId);
    const newStatus = member.status === 'active' ? 'inactive' : 'active';

    if (
      confirm(
        `Apakah Anda yakin ingin ${
          newStatus === 'inactive' ? 'menonaktifkan' : 'mengaktifkan'
        } anggota ini?`
      )
    ) {
      setMembers(
        members.map((m) =>
          m.id === memberId ? { ...m, status: newStatus } : m
        )
      );

      if (selectedMember && selectedMember.id === memberId) {
        setSelectedMember({ ...selectedMember, status: newStatus });
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
        <h1 className="text-3xl font-bold text-gray-900">Anggota Organisasi</h1>
        <p className="text-gray-600 mt-1">
          Kelola anggota aktif organisasi Anda
        </p>
      </div>

      {/* Statistics */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <StatCard
          icon={Users}
          label="Total Anggota"
          value={members.length}
          color="bg-blue-100 text-blue-700"
        />
        <StatCard
          icon={Users}
          label="Aktif"
          value={members.filter((m) => m.status === 'active').length}
          color="bg-green-100 text-green-700"
        />
        <StatCard
          icon={Users}
          label="Nonaktif"
          value={members.filter((m) => m.status === 'inactive').length}
          color="bg-red-100 text-red-700"
        />
        <StatCard
          icon={Users}
          label="Pengurus"
          value={
            members.filter(
              (m) =>
                m.status === 'active' &&
                ['Ketua', 'Sekretaris', 'Bendahara'].includes(m.position)
            ).length
          }
          color="bg-purple-100 text-purple-700"
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
          <div className="flex gap-2">
            <Filter size={18} className="text-gray-600 mt-2" />
            {['active', 'inactive', 'all'].map((status) => (
              <button
                key={status}
                onClick={() => setStatusFilter(status)}
                className={`px-4 py-2 rounded-lg font-semibold text-sm border-2 transition-colors ${
                  statusFilter === status
                    ? 'bg-blue-100 text-blue-700 border-blue-300'
                    : 'bg-gray-100 text-gray-700 border-gray-300 hover:border-blue-300'
                }`}
              >
                {status === 'active'
                  ? 'Aktif'
                  : status === 'inactive'
                    ? 'Nonaktif'
                    : 'Semua'}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Members List */}
      <div className="space-y-4">
        {filteredMembers.length === 0 ? (
          <div className="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
            <Users className="mx-auto mb-3 text-gray-400" size={48} />
            <p className="text-gray-600 font-semibold">Tidak ada anggota</p>
          </div>
        ) : (
          filteredMembers.map((member) => (
            <div
              key={member.id}
              className="bg-white p-6 rounded-2xl border-2 border-gray-200 hover:shadow-lg transition-shadow"
            >
              <div className="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                  <h3 className="text-lg font-bold text-gray-900">
                    {member.name}
                  </h3>
                  <p className="text-sm text-gray-600">{member.nim}</p>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Jabatan</p>
                  <span className="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-bold border-2 border-purple-300 inline-block mt-1">
                    {member.position}
                  </span>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Fakultas</p>
                  <p className="font-semibold text-gray-900">
                    {member.faculty}
                  </p>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Status</p>
                  <span
                    className={`px-3 py-1 rounded-full text-sm font-bold border-2 inline-block mt-1 ${
                      member.status === 'active'
                        ? 'bg-green-100 text-green-700 border-green-300'
                        : 'bg-red-100 text-red-700 border-red-300'
                    }`}
                  >
                    {member.status === 'active' ? 'Aktif' : 'Nonaktif'}
                  </span>
                </div>

                <div className="flex gap-2 flex-wrap">
                  <button
                    onClick={() => viewDetail(member)}
                    className="flex items-center gap-2 px-3 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors font-semibold border-2 border-blue-300 text-sm"
                  >
                    <Eye size={14} />
                    Detail
                  </button>
                  <button
                    onClick={() => openEditForm(member)}
                    className="flex items-center gap-2 px-3 py-2 text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors font-semibold border-2 border-amber-300 text-sm"
                  >
                    <Edit2 size={14} />
                    Ubah
                  </button>
                </div>
              </div>

              <div className="border-t-2 border-gray-200 pt-3 flex items-center justify-between">
                <p className="text-xs text-gray-500">
                  Bergabung: {member.joinDate}
                </p>
                <button
                  onClick={() => toggleStatus(member.id)}
                  className={`px-4 py-2 rounded-lg font-semibold text-sm border-2 transition-colors ${
                    member.status === 'active'
                      ? 'text-red-600 bg-red-50 hover:bg-red-100 border-red-300'
                      : 'text-green-600 bg-green-50 hover:bg-green-100 border-green-300'
                  }`}
                >
                  {member.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'}
                </button>
              </div>
            </div>
          ))
        )}
      </div>

      {/* Detail Modal */}
      {showDetail && selectedMember && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-2xl w-full shadow-2xl">
            <div className="border-b-2 border-gray-200 p-6 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-gray-900">
                {selectedMember.name}
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
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <p className="text-xs text-gray-500 uppercase">NIM</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedMember.nim}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Jabatan</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedMember.position}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Fakultas</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedMember.faculty}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Status</p>
                    <span
                      className={`px-3 py-1 rounded-full text-sm font-bold border-2 inline-block mt-1 ${
                        selectedMember.status === 'active'
                          ? 'bg-green-100 text-green-700 border-green-300'
                          : 'bg-red-100 text-red-700 border-red-300'
                      }`}
                    >
                      {selectedMember.status === 'active'
                        ? 'Aktif'
                        : 'Nonaktif'}
                    </span>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">
                      Tanggal Bergabung
                    </p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedMember.joinDate}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Email</p>
                    <p className="font-semibold text-gray-900 mt-1 break-all">
                      {selectedMember.email}
                    </p>
                  </div>
                </div>

                <div className="mt-4 pt-4 border-t-2 border-gray-200">
                  <p className="text-xs text-gray-500 uppercase">No. Telp</p>
                  <p className="font-semibold text-gray-900 mt-1">
                    {selectedMember.phone}
                  </p>
                </div>
              </div>

              <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button
                  onClick={() => setShowDetail(false)}
                  className="flex-1 px-6 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-semibold border-2 border-blue-300"
                >
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Edit Position Modal */}
      {showEditForm && editingMember && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-md w-full shadow-2xl">
            <div className="border-b-2 border-gray-200 p-6 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-gray-900">Ubah Jabatan</h2>
              <button
                onClick={() => setShowEditForm(false)}
                className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <X size={24} />
              </button>
            </div>

            <div className="p-6 space-y-4">
              <div>
                <p className="text-sm font-bold text-gray-900 mb-2">
                  Anggota: {editingMember.name}
                </p>
              </div>

              <div>
                <p className="text-sm font-bold text-gray-900 mb-2">
                  Jabatan Baru:
                </p>
                <select
                  value={newPosition}
                  onChange={(e) => setNewPosition(e.target.value)}
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                >
                  {positions.map((pos) => (
                    <option key={pos} value={pos}>
                      {pos}
                    </option>
                  ))}
                </select>
              </div>

              <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button
                  onClick={savePositionChange}
                  className="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold border-2 border-blue-700"
                >
                  Simpan
                </button>
                <button
                  onClick={() => setShowEditForm(false)}
                  className="flex-1 px-6 py-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold border-2 border-gray-300"
                >
                  Batal
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
