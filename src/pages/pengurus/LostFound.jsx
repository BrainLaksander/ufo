import React, { useState } from 'react';
import {
  Package,
  Search,
  Eye,
  CheckCircle,
  Archive,
  X,
  Filter,
  Phone,
  Mail,
} from 'lucide-react';

/**
 * Lost & Found Moderation Page - Pengurus
 *
 * Halaman moderasi Lost & Found untuk Pengurus:
 * - Melihat laporan barang hilang dan ditemukan
 * - Menghubungkan pelapor dan penemu
 * - Moderasi status laporan
 */
export default function LostFound() {
  const [items, setItems] = useState([
    {
      id: 1,
      item: 'Kunci Kucing Hitam',
      type: 'lost',
      category: 'Hewan',
      location: 'Area Kampus Utama',
      status: 'open',
      reporter: 'Mahasiswa Anonim',
      reporterEmail: 'mahasiswa@email.com',
      reporterPhone: '081234567890',
      date: '2026-03-01',
      description: 'Kucing hitam dengan tanda putih di kaki depan kiri',
      image: null,
      notes: '',
    },
    {
      id: 2,
      item: 'Dompet Merah Kulit',
      type: 'found',
      category: 'Barang Berharga',
      location: 'Kantin Area B',
      status: 'open',
      reporter: 'Security Kampus',
      reporterEmail: 'security@kampus.id',
      reporterPhone: '081987654321',
      date: '2026-02-28',
      description: 'Dompet merah kulit, kondisi baik',
      image: null,
      notes: '',
    },
    {
      id: 3,
      item: 'Tas Ransel Biru',
      type: 'lost',
      category: 'Tas & Koper',
      location: 'Perpustakaan',
      status: 'resolved',
      reporter: 'Mahasiswa Anonim',
      reporterEmail: 'mahasiswa2@email.com',
      reporterPhone: '082345678901',
      date: '2026-02-25',
      description: 'Tas ransel biru dengan logo universitas',
      image: null,
      notes: 'Sudah ditemukan dan dikembalikan kepada pemilik',
    },
    {
      id: 4,
      item: 'Jam Tangan Silver',
      type: 'found',
      category: 'Perhiasan',
      location: 'Lapangan Olahraga',
      status: 'open',
      reporter: 'Mahasiswa Anonim',
      reporterEmail: 'mahasiswa3@email.com',
      reporterPhone: '083456789012',
      date: '2026-02-26',
      description: 'Jam tangan silver, merek Casio',
      image: null,
      notes: '',
    },
  ]);

  const [showDetail, setShowDetail] = useState(false);
  const [selectedItem, setSelectedItem] = useState(null);
  const [filterType, setFilterType] = useState('all');
  const [filterStatus, setFilterStatus] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [moderationNote, setModerationNote] = useState('');

  const filteredItems = items.filter((item) => {
    let match = true;
    if (filterType !== 'all' && item.type !== filterType) match = false;
    if (filterStatus !== 'all' && item.status !== filterStatus) match = false;
    if (
      searchQuery &&
      !item.item.toLowerCase().includes(searchQuery.toLowerCase()) &&
      !item.location.toLowerCase().includes(searchQuery.toLowerCase())
    ) {
      match = false;
    }
    return match;
  });

  const viewDetail = (item) => {
    setSelectedItem(item);
    setModerationNote(item.notes);
    setShowDetail(true);
  };

  const updateStatus = (id, status) => {
    setItems(
      items.map((item) => (item.id === id ? { ...item, status } : item))
    );
    if (selectedItem && selectedItem.id === id) {
      setSelectedItem({ ...selectedItem, status });
    }
  };

  const saveModerationNote = (id) => {
    setItems(
      items.map((item) =>
        item.id === id ? { ...item, notes: moderationNote } : item
      )
    );
    if (selectedItem && selectedItem.id === id) {
      setSelectedItem({ ...selectedItem, notes: moderationNote });
    }
    alert('Catatan disimpan');
  };

  const categories = [
    'Semua Kategori',
    'Hewan',
    'Barang Berharga',
    'Tas & Koper',
    'Perhiasan',
    'Elektronik',
    'Pakaian',
    'Dokumen',
    'Lainnya',
  ];

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
        <h1 className="text-3xl font-bold text-gray-900">Lost & Found</h1>
        <p className="text-gray-600 mt-1">
          Moderasi laporan barang hilang dan ditemukan
        </p>
      </div>

      {/* Statistics */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <StatCard
          icon={Package}
          label="Total Laporan"
          value={items.length}
          color="bg-blue-100 text-blue-700"
        />
        <StatCard
          icon={Package}
          label="Terbuka"
          value={items.filter((i) => i.status === 'open').length}
          color="bg-yellow-100 text-yellow-700"
        />
        <StatCard
          icon={CheckCircle}
          label="Selesai"
          value={items.filter((i) => i.status === 'resolved').length}
          color="bg-green-100 text-green-700"
        />
        <StatCard
          icon={Archive}
          label="Diarsipkan"
          value={items.filter((i) => i.status === 'archived').length}
          color="bg-gray-100 text-gray-700"
        />
      </div>

      {/* Filters & Search */}
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
              placeholder="Cari barang atau lokasi..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
            />
          </div>

          {/* Filter Tabs */}
          <div className="flex gap-2 flex-wrap">
            <div className="flex items-center gap-2">
              <Filter size={18} className="text-gray-600" />
              <span className="font-semibold text-gray-900">Tipe:</span>
            </div>
            {['all', 'lost', 'found'].map((type) => (
              <button
                key={type}
                onClick={() => setFilterType(type)}
                className={`px-4 py-2 rounded-lg font-semibold text-sm border-2 transition-colors ${
                  filterType === type
                    ? 'bg-blue-100 text-blue-700 border-blue-300'
                    : 'bg-gray-100 text-gray-700 border-gray-300 hover:border-blue-300'
                }`}
              >
                {type === 'all'
                  ? 'Semua'
                  : type === 'lost'
                    ? 'Hilang'
                    : 'Ditemukan'}
              </button>
            ))}
          </div>

          {/* Status Filter */}
          <div className="flex gap-2 flex-wrap">
            <div className="flex items-center gap-2">
              <span className="font-semibold text-gray-900">Status:</span>
            </div>
            {['all', 'open', 'resolved', 'archived'].map((status) => (
              <button
                key={status}
                onClick={() => setFilterStatus(status)}
                className={`px-4 py-2 rounded-lg font-semibold text-sm border-2 transition-colors ${
                  filterStatus === status
                    ? 'bg-blue-100 text-blue-700 border-blue-300'
                    : 'bg-gray-100 text-gray-700 border-gray-300 hover:border-blue-300'
                }`}
              >
                {status === 'all'
                  ? 'Semua'
                  : status === 'open'
                    ? 'Terbuka'
                    : status === 'resolved'
                      ? 'Selesai'
                      : 'Diarsipkan'}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Items List */}
      <div className="space-y-4">
        {filteredItems.length === 0 ? (
          <div className="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
            <Package className="mx-auto mb-3 text-gray-400" size={48} />
            <p className="text-gray-600 font-semibold">Tidak ada laporan</p>
          </div>
        ) : (
          filteredItems.map((item) => (
            <div
              key={item.id}
              className="bg-white p-6 rounded-2xl border-2 border-gray-200 hover:shadow-lg transition-shadow"
            >
              <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                  <h3 className="text-lg font-bold text-gray-900">
                    {item.item}
                  </h3>
                  <p className="text-sm text-gray-600 mt-1">
                    {item.description}
                  </p>
                </div>

                <div className="flex gap-2 flex-wrap">
                  <span
                    className={`px-3 py-1 rounded-full text-xs font-bold border-2 ${
                      item.type === 'lost'
                        ? 'bg-red-100 text-red-700 border-red-300'
                        : 'bg-blue-100 text-blue-700 border-blue-300'
                    }`}
                  >
                    {item.type === 'lost' ? 'Hilang' : 'Ditemukan'}
                  </span>
                  <span className="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold border-2 border-gray-300">
                    {item.category}
                  </span>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Lokasi</p>
                  <p className="font-semibold text-gray-900">
                    📍 {item.location}
                  </p>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Status</p>
                  <span
                    className={`px-3 py-1 rounded text-xs font-bold border-2 inline-block${
                      item.status === 'open'
                        ? ' bg-yellow-100 text-yellow-700 border-yellow-300'
                        : item.status === 'resolved'
                          ? ' bg-green-100 text-green-700 border-green-300'
                          : ' bg-gray-100 text-gray-700 border-gray-300'
                    }`}
                  >
                    {item.status === 'open'
                      ? 'Terbuka'
                      : item.status === 'resolved'
                        ? 'Selesai'
                        : 'Diarsipkan'}
                  </span>
                </div>
              </div>

              <div className="border-t-2 border-gray-200 pt-4 flex items-center justify-between">
                <div className="text-xs text-gray-500">
                  Dilaporkan: {item.date}
                </div>
                <button
                  onClick={() => viewDetail(item)}
                  className="flex items-center gap-2 px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors font-semibold border-2 border-blue-300"
                >
                  <Eye size={16} />
                  Detail & Moderasi
                </button>
              </div>
            </div>
          ))
        )}
      </div>

      {/* Detail Modal */}
      {showDetail && selectedItem && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-3xl w-full my-8 shadow-2xl">
            <div className="border-b-2 border-gray-200 p-6 flex items-center justify-between sticky top-0 bg-white rounded-t-2xl">
              <h2 className="text-2xl font-bold text-gray-900">
                Detail Laporan L&F
              </h2>
              <button
                onClick={() => setShowDetail(false)}
                className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <X size={24} />
              </button>
            </div>

            <div className="p-6 space-y-6">
              {/* Item & Basic Info */}
              <div className="bg-gray-50 p-6 rounded-xl border-2 border-gray-200">
                <h3 className="text-2xl font-bold text-gray-900 mb-4">
                  {selectedItem.item}
                </h3>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Tipe</p>
                    <span
                      className={`px-3 py-1 rounded text-xs font-bold border-2 inline-block mt-1 ${
                        selectedItem.type === 'lost'
                          ? 'bg-red-100 text-red-700 border-red-300'
                          : 'bg-blue-100 text-blue-700 border-blue-300'
                      }`}
                    >
                      {selectedItem.type === 'lost' ? 'Hilang' : 'Ditemukan'}
                    </span>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Kategori</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedItem.category}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Lokasi</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      📍 {selectedItem.location}
                    </p>
                  </div>
                  <div>
                    <p className="text-xs text-gray-500 uppercase">Tanggal</p>
                    <p className="font-semibold text-gray-900 mt-1">
                      {selectedItem.date}
                    </p>
                  </div>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase mb-2">
                    Deskripsi
                  </p>
                  <p className="text-gray-900">{selectedItem.description}</p>
                </div>
              </div>

              {/* Reporter Contact */}
              <div className="bg-blue-50 p-6 rounded-xl border-2 border-blue-200">
                <h4 className="font-bold text-gray-900 mb-4">Kontak Pelapor</h4>
                <div className="space-y-3">
                  <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center">
                      👤
                    </div>
                    <div>
                      <p className="text-xs text-gray-500">Nama Pelapor</p>
                      <p className="font-semibold text-gray-900">
                        {selectedItem.reporter}
                      </p>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <Mail size={20} className="text-blue-600" />
                    <div>
                      <p className="text-xs text-gray-500">Email</p>
                      <p className="font-semibold text-gray-900">
                        {selectedItem.reporterEmail}
                      </p>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <Phone size={20} className="text-blue-600" />
                    <div>
                      <p className="text-xs text-gray-500">No. Telp</p>
                      <p className="font-semibold text-gray-900">
                        {selectedItem.reporterPhone}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Moderation Section */}
              <div className="border-2 border-gray-300 p-6 rounded-xl">
                <h4 className="font-bold text-gray-900 mb-4">
                  Moderasi & Catatan Internal
                </h4>

                <div className="space-y-4">
                  {/* Status Update */}
                  <div>
                    <p className="text-sm font-semibold text-gray-900 mb-2">
                      Ubah Status:
                    </p>
                    <div className="flex gap-2 flex-wrap">
                      {['open', 'resolved', 'archived'].map((status) => (
                        <button
                          key={status}
                          onClick={() => updateStatus(selectedItem.id, status)}
                          className={`px-4 py-2 rounded-lg font-semibold text-sm border-2 transition-colors ${
                            selectedItem.status === status
                              ? status === 'open'
                                ? 'bg-yellow-100 text-yellow-700 border-yellow-300'
                                : status === 'resolved'
                                  ? 'bg-green-100 text-green-700 border-green-300'
                                  : 'bg-gray-100 text-gray-700 border-gray-300'
                              : 'bg-gray-50 text-gray-700 border-gray-300 hover:bg-gray-100'
                          }`}
                        >
                          {status === 'open'
                            ? 'Terbuka'
                            : status === 'resolved'
                              ? 'Selesai'
                              : 'Arsipkan'}
                        </button>
                      ))}
                    </div>
                  </div>

                  {/* Notes */}
                  <div>
                    <p className="text-sm font-semibold text-gray-900 mb-2">
                      Catatan Internal:
                    </p>
                    <textarea
                      value={moderationNote}
                      onChange={(e) => setModerationNote(e.target.value)}
                      className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                      placeholder="Catatan moderasi (misal: sudah menghubungkan pelapor & penemu)"
                      rows={4}
                    />
                    <button
                      onClick={() => saveModerationNote(selectedItem.id)}
                      className="mt-2 px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors font-semibold border-2 border-green-300"
                    >
                      Simpan Catatan
                    </button>
                  </div>
                </div>
              </div>

              {/* Close Button */}
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
    </div>
  );
}
